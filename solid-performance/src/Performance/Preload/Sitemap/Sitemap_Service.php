<?php
/**
 * The Sitemap Service.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Sitemap;

use Countable;
use SolidWP\Performance\Preload\Sitemap\Repositories\Contracts\Sitemap_Repository;
use SolidWP\Performance\Storage\Contracts\Storage;

/**
 * The Sitemap Service.
 *
 * @package SolidWP\Performance
 */
class Sitemap_Service implements Countable {

	public const STORAGE_KEY = 'swpsp_preload_url_count';

	/**
	 * @var Crawler
	 */
	private Crawler $crawler;

	/**
	 * @var Sitemap_Repository
	 */
	private Sitemap_Repository $repository;

	/**
	 * @var Storage
	 */
	private Storage $storage;

	/**
	 * @param  Crawler            $crawler     The Sitemap Crawler.
	 * @param  Sitemap_Repository $repository  The Sitemap Repository.
	 * @param  Storage            $storage The storage system.
	 */
	public function __construct(
		Crawler $crawler,
		Sitemap_Repository $repository,
		Storage $storage
	) {
		$this->crawler    = $crawler;
		$this->repository = $repository;
		$this->storage    = $storage;
	}

	/**
	 * Crawl the sitemap for URLs and return the count.
	 *
	 * @return int
	 */
	public function crawl(): int {
		/**
		 * Filter the URLs that will be preloaded.
		 *
		 * @param string[] $urls The list of URLs that will be preloaded.
		 */
		$urls = apply_filters( 'solidwp/performance/preload/urls', $this->crawler->crawl()->urls() );

		$this->repository->create( $urls );

		$count = $this->count();

		$this->storage->set( self::STORAGE_KEY, $count );

		return $count;
	}

	/**
	 * Get the original/total count of crawled URLs.
	 *
	 * @return int
	 */
	public function total(): int {
		return (int) ( $this->storage->get( self::STORAGE_KEY ) ?? 0 );
	}

	/**
	 * Get the count of remaining crawled URLs.
	 *
	 * @return int
	 */
	public function count(): int {
		return $this->repository->count();
	}

	/**
	 * Clear the crawled URLs and total count.
	 *
	 * @return bool
	 */
	public function clear(): bool {
		$this->storage->delete( self::STORAGE_KEY );

		return $this->repository->delete();
	}
}
