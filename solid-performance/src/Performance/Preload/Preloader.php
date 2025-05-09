<?php
/**
 * The preloader that concurrently processes crawled sitemap URLs.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Preload\Contracts\Preloadable;
use SolidWP\Performance\Preload\Limiter\Batch_Limiter;
use SolidWP\Performance\Preload\Sitemap\Repositories\Contracts\Sitemap_Repository;
use SolidWP\Performance\Preload\Traits\With_Request_Params;
use SolidWP\Performance\Preload\Traits\With_Timeout;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\StellarWP\SuperGlobals\SuperGlobals;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TimeoutExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

/**
 * The preloader that concurrently processes crawled sitemap URLs.
 *
 * @package SolidWP\Performance
 */
final class Preloader implements Preloadable {

	use With_Timeout;
	use With_Request_Params;

	/**
	 * @var Sitemap_Repository
	 */
	private Sitemap_Repository $repository;

	/**
	 * @var Preload_Scheduler
	 */
	private Preload_Scheduler $scheduler;

	/**
	 * @var Client
	 */
	private Client $client;

	/**
	 * @var Batch_Limiter
	 */
	private Batch_Limiter $batch_limiter;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @var Preload_Mode_Manager
	 */
	private Preload_Mode_Manager $preload_mode_manager;

	/**
	 * @var Trigger_Url
	 */
	private Trigger_Url $trigger_url;

	/**
	 * How many seconds to sleep between batches.
	 *
	 * @var int
	 */
	private int $sleep;

	/**
	 * The maximum number of errors before high performance mode is disabled.
	 *
	 * @var int
	 */
	private int $max_errors_per_batch;

	/**
	 * @var int[]
	 */
	private array $error_codes;

	/**
	 * @param Sitemap_Repository   $repository The Sitemap Repository.
	 * @param Preload_Scheduler    $scheduler The Preload Scheduler.
	 * @param Client               $client The Preload Client.
	 * @param Batch_Limiter        $batch_limiter The batch rate limiter.
	 * @param LoggerInterface      $logger The logger.
	 * @param Preload_Mode_Manager $preload_mode_manager The preload mode manager.
	 * @param Trigger_Url          $trigger_url The home URL to use to trigger a preloader batch.
	 * @param int                  $sleep How many seconds to sleep between batches.
	 * @param int                  $max_errors_per_batch The maximum number of errors before high performance mode is disabled.
	 * @param int[]                $error_codes The http response codes considered errors.
	 */
	public function __construct(
		Sitemap_Repository $repository,
		Preload_Scheduler $scheduler,
		Client $client,
		Batch_Limiter $batch_limiter,
		LoggerInterface $logger,
		Preload_Mode_Manager $preload_mode_manager,
		Trigger_Url $trigger_url,
		int $sleep = 1,
		int $max_errors_per_batch = 3,
		array $error_codes = [
			429,
			503,
			508,
		]
	) {
		$this->repository           = $repository;
		$this->scheduler            = $scheduler;
		$this->client               = $client;
		$this->batch_limiter        = $batch_limiter;
		$this->logger               = $logger;
		$this->preload_mode_manager = $preload_mode_manager;
		$this->trigger_url          = $trigger_url;
		$this->sleep                = $sleep;
		$this->max_errors_per_batch = $max_errors_per_batch;
		$this->error_codes          = $error_codes;
	}

	/**
	 * Send a preload request.
	 *
	 * @action solidwp/performance/cache/purge/url/after
	 *
	 * @param  string               $url The full or relative URL to preload.
	 * @param  array<string, mixed> $params The query parameters to attach to the request.
	 *
	 * @throws TransportExceptionInterface When an error happens at the transport level.
	 *
	 * @return ResponseInterface
	 */
	public function preload_url( string $url, array $params = [] ): ResponseInterface {
		/**
		 * Filters the Accept-Encoding HTTP header to decide the order of which cache file type to create when preloading.
		 *
		 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Accept-Encoding
		 *
		 * @param string $accept_encoding The valid Accept-Encoding string.
		 */
		$encoding = apply_filters( 'solidwp/performance/preload/accept_encoding', 'gzip, deflate, br, zstd' );

		return $this->client->get(
			$url,
			$params,
			[
				'Cache-Control'   => 'no-cache',
				'Accept-Encoding' => $encoding,
			],
			[
				'timeout' => $this->get_request_timeout(),
			]
		);
	}

	/**
	 * Preload a batch of crawled sitemap URLs.
	 *
	 * @action template_redirect
	 *
	 * @return void
	 */
	public function preload(): void {
		// The preloader query string parameter isn't included.
		if ( ! SuperGlobals::get_get_var( self::PRELOAD_TRIGGER_PARAM ) ) {
			return;
		}

		// This should only run on the frontend.
		if ( is_admin() || wp_doing_ajax() || wp_is_serving_rest_request() ) {
			return;
		}

		// Check if the preloader is actually running.
		if ( ! $this->scheduler->is_running() ) {
			return;
		}

		// We're out of URls to crawl, complete the preloading.
		if ( $this->repository->count() <= 0 ) {
			$this->logger->debug( 'Completing Preloader: No more URLs' );
			$this->scheduler->complete();

			return;
		}

		$batch_size = $this->batch_limiter->get_batch_size();

		$this->logger->debug(
			'URL batch size: {batch_size}',
			[
				'batch_size' => $batch_size,
			]
		);

		// Only sleep if the batch limiter is at the minimum.
		if ( $batch_size === Batch_Limiter::MIN_BATCH_SIZE ) {
			$this->logger->debug(
				'Minimum batch size detected. Sleeping for {sleep_seconds}s',
				[
					'sleep_seconds' => $this->sleep,
				]
			);

			sleep( $this->sleep );
		}

		$urls = $this->repository->pull( $batch_size );

		$responses = [];

		foreach ( $urls as $url ) {
			$params = [];

			if ( $this->scheduler->is_forced() ) {
				// We're doing an entire site preload, cache bust every request.
				$params = $this->get_preload_cache_bust_params();
			}

			try {
				$responses[] = $this->preload_url( $url, $params );
			} catch ( TimeoutExceptionInterface $e ) {
				// We expected timeouts.
				$this->logger->debug( $e->getMessage() );
			}
		}

		if ( $responses ) {
			// Trigger the next batch, use the homepage because it has to exist, right?
			try {
				$responses[] = $this->preload_url(
					$this->trigger_url->get(),
					$this->get_preload_trigger_params()
				);
			} catch ( TimeoutExceptionInterface $e ) {
				// We expected timeouts.
				$this->logger->debug( $e->getMessage() );
			}
		}

		$errors = 0;

		// Process responses for the batch.
		foreach ( $responses as $response ) {
			try {
				$response->getHeaders();
			} catch ( ServerExceptionInterface | ClientExceptionInterface $e ) {
				$this->logger->debug(
					$e->getMessage(),
					[
						'exception' => $e,
					]
				);

				try {
					$status_code = $e->getResponse()->getStatusCode();

					// Check if we're hitting any arbitrary resource limits or the server is rate limiting us.
					if ( ! in_array( $status_code, $this->error_codes, true ) ) {
						continue;
					}

					++$errors;

					if ( $errors < $this->max_errors_per_batch ) {
						continue;
					}

					if ( $this->preload_mode_manager->is_high_performance_mode() ) {
						$this->logger->error(
							'Max response errors "{errors}" reached. Disabling high performance mode.',
							[
								'errors'           => $errors,
								'last_status_code' => $status_code,
								'response_info'    => $e->getResponse()->getInfo(),
							]
						);

						// This is currently permanent, but can be reversed with `wp solid perf preload perf-mode on`.
						$this->preload_mode_manager->disable_high_performance_mode();
					} else {
						$this->logger->warning(
							'Max response errors "{errors}" reached. High performance mode already disabled!',
							[
								'errors'           => $errors,
								'last_status_code' => $status_code,
								'response_info'    => $e->getResponse()->getInfo(),
							]
						);
					}
				} catch ( TransportExceptionInterface $e ) {
					$this->logger->warning(
						'Response threw exception when checking status code',
						[
							'exception' => $e,
						]
					);
				}
			} catch ( Throwable $e ) {
				// Ignore any requests that failed for any other reason.
				$this->logger->debug( $e->getMessage() );
			} finally {
				$response->cancel();
			}
		}
	}
}
