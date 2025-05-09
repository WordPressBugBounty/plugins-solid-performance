<?php
/**
 * The route for clearing the page cache.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\API\Routes\Page_Cache;

use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Page_Cache;
use SolidWP\Performance\Preload\Preload_Scheduler;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class that handles the clear cache route.
 *
 * @since 0.1.0
 *
 * @package SolidWP|Performance
 */
class Clear extends Base_Route {

	/**
	 * @var Page_Cache
	 */
	private Page_Cache $page_cache;

	/**
	 * @var Preload_Scheduler
	 */
	private Preload_Scheduler $preloader;

	/**
	 * @param  Page_Cache        $page_cache  The page cache object.
	 * @param  Preload_Scheduler $preloader   The preloader.
	 */
	public function __construct( Page_Cache $page_cache, Preload_Scheduler $preloader ) {
		$this->page_cache = $page_cache;
		$this->preloader  = $preloader;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/clear';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_methods() {
		return WP_REST_Server::CREATABLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function callback( WP_REST_Request $request ) {
		if ( $this->preloader->is_running() ) {
			return new WP_Error(
				'solid_performance_page_cache_clear_error',
				__( 'The site is currently preloading. Cancel preloading or try again later.', 'solid-performance' ),
			);
		}

		$result = $this->page_cache->clear();

		if ( ! $result ) {
			return new WP_Error(
				'solid_performance_page_cache_clear_error',
				__( 'Page cache could not be cleared', 'solid-performance' ),
			);
		}

		return new WP_REST_Response(
			[
				'code'    => 'solid_performance_page_cache_cleared',
				'message' => __( 'Page cache cleared successfully', 'solid-performance' ),
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
				'code'    => [
					'description' => esc_html__( 'The identification code of the action.', 'solid-performance' ),
					'type'        => 'string',
					'enum'        => [
						'solid_performance_page_cache_cleared',
						'solid_performance_page_cache_clear_error',
					],
					'readonly'    => true,
				],
				'message' => [
					'description' => esc_html__( 'The formatted message of the action change.', 'solid-performance' ),
					'type'        => 'string',
					'readonly'    => true,
				],
			],
		];
	}
}
