<?php
/**
 * The route to get the current Cache Delivery state.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\API\Routes\Page_Cache;

use SolidWP\Performance\Admin\Settings_Page;
use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;
use SolidWP\Performance\Cache_Delivery\Manager_Collection;
use SolidWP\Performance\StellarWP\Arrays\Arr;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The route to get the current Cache Delivery state.
 *
 * @package SolidWP\Performance
 */
final class Cache_Delivery extends Base_Route {

	public const CODE_FAILED = 'solid_performance_cache_delivery_failed';

	public const PARAM_NGINX_RULES = 'nginx_rules';

	/**
	 * @var Manager_Collection
	 */
	private Manager_Collection $manager_collection;

	/**
	 * @param Manager_Collection $manager_collection The cache delivery manager collection.
	 */
	public function __construct( Manager_Collection $manager_collection ) {
		$this->manager_collection = $manager_collection;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/cache-delivery';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_methods() {
		return WP_REST_Server::READABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_arguments(): array {
		return [
			WP_REST_Server::READABLE => [
				self::PARAM_NGINX_RULES => [
					'type'        => 'boolean',
					'description' => esc_html__( 'Whether to include the current rules from the swpsp-nginx.conf file in the response.', 'solid-performance' ),
					'required'    => false,
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function callback( WP_REST_Request $request ) {
		$htaccess = $this->manager_collection->get( Cache_Delivery_Type::HTACCESS );
		$nginx    = $this->manager_collection->get( Cache_Delivery_Type::NGINX );

		$response = [
			'cacheDelivery' => [
				'htaccess' => [
					'supported' => $htaccess->supported(),
					'hasRules'  => $htaccess->has_rules(),
				],
				'nginx'    => [
					'supported' => $nginx->supported(),
					'hasRules'  => $nginx->has_rules(),
				],
			],
		];

		if ( $request->get_param( self::PARAM_NGINX_RULES ) ) {
			try {
				$response = Arr::set( $response, 'cacheDelivery.nginx.rules', $nginx->get() );
			} catch ( CacheDeliveryReadException $e ) {
				return new WP_Error(
					self::CODE_FAILED,
					$e->getMessage(),
				);
			}
		}

		return new WP_REST_Response( $response );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see Settings_Page::admin_head()
	 */
	public function schema_callback(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'cache_delivery_status',
			'type'       => 'object',
			'properties' => [
				'cacheDelivery' => [
					'description' => esc_html__( 'The cache delivery status for each method.', 'solid-performance' ),
					'type'        => 'object',
					'properties'  => [
						'htaccess' => [
							'type'       => 'object',
							'properties' => [
								'supported' => [
									'description' => esc_html__( 'Whether .htaccess delivery is supported.', 'solid-performance' ),
									'type'        => 'boolean',
									'readonly'    => true,
								],
								'hasRules'  => [
									'description' => esc_html__( 'Whether .htaccess rules are present.', 'solid-performance' ),
									'type'        => 'boolean',
									'readonly'    => true,
								],
								'path'      => [
									'description' => esc_html__( 'The optional path to the configuration file.', 'solid-performance' ),
									'type'        => 'string',
									'readonly'    => true,
									'required'    => false,
								],
							],
						],
						'nginx'    => [
							'type'       => 'object',
							'properties' => [
								'supported' => [
									'description' => esc_html__( 'Whether Nginx delivery is supported.', 'solid-performance' ),
									'type'        => 'boolean',
									'readonly'    => true,
								],
								'hasRules'  => [
									'description' => esc_html__( 'Whether Nginx rules are present.', 'solid-performance' ),
									'type'        => 'boolean',
									'readonly'    => true,
								],
								'rules'     => [
									'description' => esc_html__( 'The contents of the swpsp-nginx.conf.', 'solid-performance' ),
									'type'        => 'string',
									'readonly'    => true,
									'required'    => false,
								],
							],
						],
					],
				],
			],
		];
	}
}
