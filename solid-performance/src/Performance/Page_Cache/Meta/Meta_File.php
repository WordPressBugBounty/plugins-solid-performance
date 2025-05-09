<?php
/**
 * The meta file provides a single object to access the meta file
 * cache path for a URL.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta;

use SolidWP\Performance\Page_Cache\Cache_Path;

/**
 * The meta file provides a single object to access the meta file
 * cache path for a URL.
 *
 * @package SolidWP\Performance
 */
final class Meta_File {

	public const EXT = '.meta.php';

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @param Cache_Path $cache_path The cache path.
	 */
	public function __construct( Cache_Path $cache_path ) {
		$this->cache_path = $cache_path;
	}

	/**
	 * Returns the full server path to a meta file associated with a URL.
	 *
	 * @param string $url The URL the meta is associated with.
	 *
	 * @throws \RuntimeException When URL does not have valid host.
	 *
	 * @return string e.g. /app/wp-content/cache/page/local.test.com/test-post.meta.php
	 */
	public function get_path_from_url( string $url ): string {
		return $this->cache_path->get_path_from_url( $url ) . self::EXT;
	}
}
