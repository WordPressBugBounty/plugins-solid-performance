<?php
/**
 * The provider responsible for registering API classes with the container & WordPress.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\API;

use SolidWP\Performance\API\Routes\Page_Cache\Cache_Count;
use SolidWP\Performance\API\Routes\Page_Cache\Cache_Delivery;
use SolidWP\Performance\API\Routes\Page_Cache\Clear;
use SolidWP\Performance\API\Routes\Page_Cache\Debug;
use SolidWP\Performance\API\Routes\Page_Cache\Nginx;
use SolidWP\Performance\API\Routes\Page_Cache\Off;
use SolidWP\Performance\API\Routes\Page_Cache\On;
use SolidWP\Performance\API\Routes\Page_Cache\Preload;
use SolidWP\Performance\API\Routes\Page_Cache\Htaccess;
use SolidWP\Performance\API\Routes\Page_Cache\Status;
use SolidWP\Performance\API\Routes\Page_Cache\Regenerate;
use SolidWP\Performance\Contracts\Service_Provider;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The provider responsible for registering API classes with the container & WordPress.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
class Provider extends Service_Provider {

	/**
	 * The namespace of the plugin's REST API routes.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private string $namespace = 'solid-performance/v1';

	/**
	 * All the endpoints that should be registered with the REST API.
	 *
	 * @since 0.1.0
	 *
	 * @var array<int,string>
	 */
	private array $endpoints = [
		Clear::class,
		Debug::class,
		Off::class,
		On::class,
		Regenerate::class,
		Status::class,
		Cache_Count::class,
		Preload::class,
		Htaccess::class,
		Nginx::class,
		Cache_Delivery::class,
	];

	/**
	 * {@inheritdoc}
	 */
	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_all_endpoints' ] );
	}

	/**
	 * Registers all endpoints of the REST API.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_all_endpoints(): void {
		foreach ( $this->endpoints as $class ) {
			/** @var Route $endpoint */
			$endpoint = $this->container->get( $class );
			$methods  = $endpoint->get_methods();

			if ( is_array( $methods ) ) {
				foreach ( $methods as $method ) {
					$args = $endpoint->get_arguments();
					$args = $args[ $method ] ?? [];

					register_rest_route(
						$this->namespace,
						$endpoint->get_path(),
						[
							[
								'methods'             => $method,
								'callback'            => [ $endpoint, 'callback' ],
								'args'                => $args,
								'permission_callback' => [ $endpoint, 'permission_callback' ],
							],
							'schema' => [ $endpoint, 'schema_callback' ],
						],
					);
				}
			} else {
				register_rest_route(
					$this->namespace,
					$endpoint->get_path(),
					[
						[
							'methods'             => $endpoint->get_methods(),
							'callback'            => [ $endpoint, 'callback' ],
							'args'                => $endpoint->get_arguments(),
							'permission_callback' => [ $endpoint, 'permission_callback' ],
						],
						'schema' => [ $endpoint, 'schema_callback' ],
					],
				);
			}
		}
	}
}
