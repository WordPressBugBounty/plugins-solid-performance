<?php
/**
 * The route for starting, cancelling and getting preloading progress updates.
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\API\Routes\Page_Cache;

use InvalidArgumentException;
use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Preload\Exceptions\PreloaderInProgressException;
use SolidWP\Performance\Preload\Exceptions\PreloadException;
use SolidWP\Performance\Preload\Monitor\Exceptions\PreloadMonitorMaxRetriesException;
use SolidWP\Performance\Preload\Preload_Scheduler;
use SolidWP\Performance\Preload\State\Enums\Status;
use SolidWP\Performance\Storage\Exceptions\InvalidKeyException;
use Throwable;
use WP_Error;
use WP_Http;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The route for starting, cancelling and getting preloading progress updates.
 *
 * @package SolidWP\Performance
 */
final class Preload extends Base_Route {

	public const PARAM_FORCE     = 'force';
	private const CODE_RUNNING   = 'solid_performance_preloader_already_running';
	private const CODE_STARTED   = 'solid_performance_preloader_started';
	private const CODE_COMPLETED = 'solid_performance_preloader_completed';
	private const CODE_CANCELED  = 'solid_performance_preloader_canceled';
	private const CODE_STALLED   = 'solid_performance_preloader_stalled';
	private const CODE_FAILED    = 'solid_performance_preloader_failed';

	private const CODES = [
		self::CODE_RUNNING,
		self::CODE_STARTED,
		self::CODE_COMPLETED,
		self::CODE_CANCELED,
		self::CODE_STALLED,
		self::CODE_FAILED,
	];

	/**
	 * @var Preload_Scheduler
	 */
	private Preload_Scheduler $preloader;

	/**
	 * @param  Preload_Scheduler $preloader The preload scheduler.
	 */
	public function __construct( Preload_Scheduler $preloader ) {
		$this->preloader = $preloader;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/preload';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_methods() {
		return [
			WP_REST_Server::READABLE,
			WP_REST_Server::CREATABLE,
			WP_REST_Server::DELETABLE,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function callback( WP_REST_Request $request ) {
		$is_preloading = $this->preloader->is_running();

		// Start the preloader.
		if ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			return $this->start_preloader( $request );
		} elseif ( $request->get_method() === WP_REST_Server::DELETABLE ) {
			return $this->preloader_canceled();
		}

		$state = $this->preloader->state()->get();

		try {
			// sessionStorage on the frontend prevents this from being displayed over and over.
			if ( ! $is_preloading ) {
				if ( $state->status === Status::COMPLETED ) {
					// Preloading completed normally.
					return new WP_REST_Response(
						[
							'code'      => self::CODE_COMPLETED,
							/* translators: %s: The human-readable duration. */
							'message'   => sprintf( __( 'Preloading completed in %s.', 'solid-performance' ), $state->duration ),
							'running'   => false,
							'progress'  => $this->preloader->progress(),
							'preloadId' => $state->id,
						]
					);
				} elseif ( $state->status === Status::CANCELED ) {
					return new WP_REST_Response(
						[
							'code'      => self::CODE_CANCELED,
							/* translators: %s: The source of what initiated or canceled the preloader. */
							'message'   => sprintf( __( 'Preloading canceled via "%s".', 'solid-performance' ), $state->source ),
							'running'   => false,
							'progress'  => $this->preloader->progress(),
							'preloadId' => $state->id,
						]
					);
				}
			}

			// Send the current progress.
			return new WP_REST_Response(
				[
					'running'  => $is_preloading,
					'source'   => $state->source,
					'progress' => $this->preloader->progress(),
				]
			);
		} catch ( PreloadMonitorMaxRetriesException $e ) {
			// The preloader was stalled and tried to restart until the max retries were reached.
			$this->preloader->fail( $e->getMessage() );

			return new WP_REST_Response(
				[
					'code'      => self::CODE_FAILED,
					/* translators: %s: The error message. */
					'message'   => sprintf( __( 'Preloading Failed: %s', 'solid-performance' ), $e->getMessage() ),
					'running'   => false,
					'progress'  => 0,
					'preloadId' => $state->id,
				]
			);
		} catch ( PreloadException $e ) {
			// The preloader is stalled and is being retried.
			return new WP_REST_Response(
				[
					'code'      => self::CODE_STALLED,
					/* translators: %s: The error message. */
					'message'   => sprintf( __( 'Attempting to restart the Preloader. An error occurred: %s', 'solid-performance' ), $e->getMessage() ),
					'running'   => true,
					'progress'  => 0,
					'preloadId' => $state->id,
				]
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_arguments(): array {
		return [
			WP_REST_Server::CREATABLE => [
				self::PARAM_FORCE => [
					'type'        => 'boolean',
					'description' => esc_html__( 'Whether to force a full site preload.', 'solid-performance' ),
					'default'     => false,
					'required'    => false,
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function schema_callback(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'preload status',
			'type'       => 'object',
			'properties' => [
				'code'      => [
					'description' => esc_html__( 'The identification code of the preloading state.', 'solid-performance' ),
					'type'        => 'string',
					'enum'        => self::CODES,
					'readonly'    => true,
				],
				'message'   => [
					'description' => esc_html__( 'The message describing the current preloading state or action result.', 'solid-performance' ),
					'type'        => 'string',
					'readonly'    => true,
				],
				'source'    => [
					'description' => esc_html__( 'The source of where the preloader started running.', 'solid-performance' ),
					'type'        => 'string',
					'enum'        => [
						'web',
						'cli',
					],
					'readonly'    => true,
				],
				'running'   => [
					'description' => esc_html__( 'Indicates whether preloading is currently running.', 'solid-performance' ),
					'type'        => 'boolean',
					'readonly'    => true,
				],
				'progress'  => [
					'description' => esc_html__( 'The progress of the preloading process as a percentage.', 'solid-performance' ),
					'type'        => 'integer',
					'minimum'     => 0,
					'maximum'     => 100,
					'readonly'    => true,
				],
				'preloadId' => [
					'description' => esc_html__( 'The preload ID to determine if the current session has shown it yet.', 'solid-performance' ),
					'type'        => 'string',
					'readonly'    => true,
				],
			],
			'oneOf'      => [
				[
					'required' => [ 'code', 'message' ],
				],
				[
					'required' => [ 'running', 'message', 'preloadId' ],
				],
				[
					'required' => [ 'running', 'source', 'progress' ],
				],
				[
					'required' => [ 'code', 'message', 'running', 'progress', 'preloadId' ],
				],
			],
		];
	}

	/**
	 * Start the preloader.
	 *
	 * @param WP_REST_Request $request The current request.
	 *
	 * @throws PreloadException If an error occurs when we fail the preloader.
	 * @throws InvalidKeyException If an invalid storage key is used.
	 * @throws InvalidArgumentException If an invalid source or status is provided.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	private function start_preloader( WP_REST_Request $request ) {
		$force = (bool) $request->get_param( self::PARAM_FORCE );

		try {
			$this->preloader->start( $force );
		} catch ( PreloaderInProgressException $e ) {
			return new WP_Error(
				self::CODE_RUNNING,
				sprintf(
					/* translators: %s: The source of what initiated or canceled the preloader. */
					__( 'A preloader is already running via "%s".', 'solid-performance' ),
					$this->preloader->state()->get()->source
				),
				[
					'status' => WP_Http::CONFLICT,
				]
			);
		} catch ( PreloadException $e ) {
			return new WP_Error(
				self::CODE_RUNNING,
				/* translators: %s: The error message. */
				sprintf( __( 'Warning: %s', 'solid-performance' ), $e->getMessage() )
			);
		} catch ( Throwable $e ) {
			$this->preloader->fail( $e->getMessage() );

			return new WP_Error(
				self::CODE_COMPLETED,
				/* translators: %s: The error message. */
				sprintf( __( 'An error occurred: %s', 'solid-performance' ), $e->getMessage() )
			);
		}

		$force_text = $force ? __( 'Preload & Refresh All', 'solid-performance' ) : __( 'Preload Uncached Pages', 'solid-performance' );

		return new WP_REST_Response(
			[
				'code'    => self::CODE_STARTED,
				'message' => $force_text . ': ' . sprintf(
					/* translators: %s: The number of URLs to preload. */
					_n(
						'Preparing to preload %s crawled sitemap URL.',
						'Preparing to preload %s crawled sitemap URLs.',
						$this->preloader->count(),
						'solid-performance'
					),
					number_format_i18n( (float) $this->preloader->count() )
				),
			],
			WP_Http::ACCEPTED
		);
	}

	/**
	 * The preloader was canceled.
	 *
	 * @return WP_REST_Response
	 */
	private function preloader_canceled(): WP_REST_Response {
		$state = $this->preloader->cancel()->state()->get();

		return new WP_REST_Response(
			[
				'running'   => $this->preloader->is_running(),
				'message'   => __( 'Preloading successfully canceled.', 'solid-performance' ),
				'preloadId' => $state->id,
			]
		);
	}
}
