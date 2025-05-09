<?php
/**
 * The Sitemap Repository that utilizes the wp_options table to store URls.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Sitemap\Repositories;

use SolidWP\Performance\Preload\Sitemap\Repositories\Contracts\Sitemap_Repository;

/**
 * The Sitemap Repository that utilizes the wp_options table to store URls.
 *
 * @package SolidWP\Performance
 */
final class Sitemap_Option_Repository implements Sitemap_Repository {

	public const OPTION = 'solid_performance_urls';

	/**
	 * Get all URLs from the repository.
	 *
	 * @return string[]
	 */
	public function all(): array {
		return (array) get_option( self::OPTION, [] );
	}

	/**
	 * Create a new set of URLs in the repository.
	 *
	 * @param string[] $urls The list of URLs to store in the repository.
	 *
	 * @return bool
	 */
	public function create( array $urls ): bool {
		return update_option( self::OPTION, $urls, false );
	}

	/**
	 * Update the set of URLs in the repository.
	 *
	 * @param string[] $urls The list of URLs to replace in the repository.
	 *
	 * @return bool
	 */
	public function update( array $urls ): bool {
		return $this->create( $urls );
	}

	/**
	 * Delete all URLs from the repository.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return delete_option( self::OPTION );
	}

	/**
	 * Pull (fetch and delete) a batch of URLs from the repository.
	 *
	 * @param int $count The number of URLs to retrieve from the repository.
	 *
	 * @return string[]
	 */
	public function pull( int $count = 50 ): array {
		$urls  = $this->all();
		$batch = array_splice( $urls, 0, $count );

		$this->update( $urls );

		return $batch;
	}

	/**
	 * Get the count of URLs in the repository.
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->all() );
	}
}
