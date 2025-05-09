<?php
/**
 * Removes the old page cache directory, if it exists.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Once;

use SolidWP\Performance\Page_Cache\Cache_Path;
use SolidWP\Performance\Update\Tasks\Contracts\Task;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Removes the old page cache directory, if it exists.
 *
 * @package SolidWP\Performance
 */
final class Clean_Up_Old_Cache_Dir implements Task {

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
	 * Check if the old page cache directory exists.
	 *
	 * @return bool
	 */
	public function should_run(): bool {
		return is_dir( $this->get_old_page_cache_dir() );
	}

	/**
	 * Delete the old page cache directory.
	 *
	 * @return void
	 */
	public function run(): void {
		swpsp_direct_filesystem()->rmdir( $this->get_old_page_cache_dir(), true );
	}

	/**
	 * Get the old < 1.5.0, page cache directory path.
	 *
	 * @return string
	 */
	private function get_old_page_cache_dir(): string {
		$cache_dir = $this->cache_path->get_page_cache_dir();

		return str_replace( '/solid-performance', '', $cache_dir );
	}
}
