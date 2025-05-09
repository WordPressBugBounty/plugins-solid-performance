<?php
/**
 * The Sitemap Repository contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Sitemap\Repositories\Contracts;

use Countable;

/**
 * The Sitemap Repository contract.
 *
 * @package SolidWP\Performance
 */

interface Sitemap_Repository extends Countable {

	/**
	 * Get all URLs from the repository.
	 *
	 * @return string[]
	 */
	public function all(): array;

	/**
	 * Create a new set of URLs in the repository.
	 *
	 * @param string[] $urls The list of URLs to store in the repository.
	 *
	 * @return bool
	 */
	public function create( array $urls ): bool;

	/**
	 * Update the set of URLs in the repository.
	 *
	 * @param string[] $urls The list of URLs to replace in the repository.
	 *
	 * @return bool
	 */
	public function update( array $urls ): bool;

	/**
	 * Delete all URLs from the repository.
	 *
	 * @return bool
	 */
	public function delete(): bool;

	/**
	 * Pull (fetch and delete) a batch of URLs from the repository.
	 *
	 * @param int $count The number of URLs to retrieve from the repository.
	 *
	 * @return string[]
	 */
	public function pull( int $count = 50 ): array;

	/**
	 * Get the count of URLs in the repository.
	 *
	 * @return int
	 */
	public function count(): int;
}
