<?php
/**
 * The route for getting how many pages are cached.
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\API\Routes\Page_Cache;

use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Page_Cache\Cache_Path;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The route for getting how many pages are cached.
 *
 * @package SolidWP\Performance
 */
final class Cache_Count extends Base_Route {

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @param  Cache_Path $cache_path The cache path object.
	 */
	public function __construct( Cache_Path $cache_path ) {
		$this->cache_path = $cache_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/cache-count';
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
	public function callback( WP_REST_Request $request ) {
		return new WP_REST_Response(
			[
				'count' => $this->cache_path->count(),
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function schema_callback(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'cache count',
			'type'       => 'object',
			'properties' => [
				'count' => [
					'description' => esc_html__( 'How many cached pages this site has across all compression types', 'solid-performance' ),
					'type'        => 'int',
					'readonly'    => true,
				],
			],
		];
	}
}
