<?php
/**
 * The route to manage generating and removing our htaccess rules.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\API\Routes\Page_Cache;

use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Cache_Delivery\Htaccess\Manager;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The route to manage generating and removing our htaccess rules.
 *
 * @package SolidWP\Performance
 */
final class Htaccess extends Base_Route {

	public const CODE_SUCCESS = 'solid_performance_htaccess_success';
	public const CODE_FAILED  = 'solid_performance_htaccess_failed';

	/**
	 * @var Manager
	 */
	private Manager $manager;

	/**
	 * @param Manager $manager The htaccess manager.
	 */
	public function __construct( Manager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/htaccess';
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
		if ( $request->get_method() === WP_REST_Server::DELETABLE ) {
			$result = $this->manager->remove_rules();

			if ( ! $result ) {
				return new WP_Error(
					self::CODE_FAILED,
					__( 'Failed to remove htaccess rules', 'solid-performance' ),
				);
			}

			return new WP_REST_Response(
				[
					'code'    => self::CODE_SUCCESS,
					'message' => __( 'Htaccess rules removed', 'solid-performance' ),
				]
			);
		} elseif ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			$result = $this->manager->add_rules( true );

			if ( ! $result ) {
				return new WP_Error(
					self::CODE_FAILED,
					__( 'Failed to regenerate htaccess rules', 'solid-performance' ),
				);
			}

			return new WP_REST_Response(
				[
					'code'    => self::CODE_SUCCESS,
					'message' => __( 'Htaccess rules regenerated', 'solid-performance' ),
				]
			);
		}

		return new WP_REST_Response(
			[
				'code'      => self::CODE_SUCCESS,
				'has_rules' => $this->manager->has_rules(),
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function schema_callback(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'setting',
			'type'       => 'object',
			'properties' => [
				'code'      => [
					'description' => esc_html__( 'The identification code of the htaccess action.', 'solid-performance' ),
					'type'        => 'string',
					'enum'        => [
						self::CODE_SUCCESS,
						self::CODE_FAILED,
					],
					'readonly'    => true,
				],
				'message'   => [
					'description' => esc_html__( 'The formatted message of the htaccess action.', 'solid-performance' ),
					'type'        => 'string',
					'readonly'    => true,
				],
				'has_rules' => [
					'description' => esc_html__( 'Whether our rules are present in the .htaccess file.', 'solid-performance' ),
					'type'        => 'boolean',
					'readonly'    => true,
				],
			],
		];
	}
}
