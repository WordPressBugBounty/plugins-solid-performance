<?php
/**
 * A single place to get cache path directories.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Brotli;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Gzip;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Html;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Zstd;
use UnexpectedValueException;

/**
 * A single place to get cache path directories.
 *
 * @package SolidWP\Performance
 */
class Cache_Path {

	/**
	 * @var Request_Scheme_Resolver
	 */
	private Request_Scheme_Resolver $scheme_resolver;

	/**
	 * The cache directory, e.g. /app/wp-content/cache/solid-performance.
	 *
	 * @var string
	 */
	private string $cache_dir;

	/**
	 * @param Request_Scheme_Resolver $scheme_resolver The http scheme resolver.
	 * @param string                  $cache_dir The cache directory, e.g. /app/wp-content/cache/solid-performance.
	 */
	public function __construct( Request_Scheme_Resolver $scheme_resolver, string $cache_dir ) {
		$this->scheme_resolver = $scheme_resolver;
		$this->cache_dir       = $cache_dir;
	}

	/**
	 * Returns the server path to the cache directory.
	 *
	 * @example /app/wp-content/cache/solid-performance
	 *
	 * @return string
	 */
	public function get_cache_dir(): string {
		return $this->cache_dir;
	}

	/**
	 * Get the path to where pages are cached.
	 *
	 * @example  /app/wp-content/cache/solid-performance/page
	 *
	 * @return string
	 */
	public function get_page_cache_dir(): string {
		return $this->get_cache_dir() . '/page';
	}

	/**
	 * Get the host site cache directory.
	 *
	 * @example /app/wp-content/cache/solid-performance/page/www.wordpress.test
	 *
	 * @return string
	 */
	public function get_site_cache_dir(): string {
		$path      = $this->get_page_cache_dir();
		$site_host = wp_parse_url( get_site_url(), PHP_URL_HOST );


		return $path . DIRECTORY_SEPARATOR . $site_host;
	}

	/**
	 * Get the HTTP scheme, e.g. https or http.
	 *
	 * @return string
	 */
	public function get_scheme(): string {
		return $this->scheme_resolver->get_scheme();
	}

	/**
	 * Converts a URL into a directory structure.
	 *
	 * All cached requests will be saved to a directory structure that matches
	 * the relative URL. This provides a way to consistently get the cached
	 * resource from the URL.
	 *
	 * @example /app/wp-content/cache/solid-performance/page/local.test.com/test-post/index-https
	 *
	 * @throws RuntimeException When URL does not have valid host.
	 *
	 * @param string $url The full URL of the request (e.g. https://solidwp.com/about).
	 *
	 * @return string
	 */
	public function get_path_from_url( string $url ): string {
		$url_parts = parse_url( $url ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url

		if ( ! isset( $url_parts['host'] ) || $url_parts['host'] === '' ) {
			throw new RuntimeException( 'URL needs a valid host.' );
		}

		$host   = trim( $url_parts['host'], '/' );
		$path   = trim( $url_parts['path'] ?? '', '/' );
		$scheme = $this->get_scheme();

		$cache_path = "{$this->get_page_cache_dir()}/$host";

		if ( $path !== '' ) {
			$cache_path .= "/$path";
		}

		return "$cache_path/index-$scheme";
	}

	/**
	 * Recursively count how many cache files we have.
	 *
	 * We count 1 supported extension per directory as a single file.
	 *
	 * @param  string $path The optional path.
	 *
	 * @return int
	 */
	public function count( string $path = '' ): int {
		if ( ! $path ) {
			$path = $this->get_site_cache_dir();
		}

		if ( ! file_exists( $path ) ) {
			return 0;
		}

		try {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS )
			);
		} catch ( UnexpectedValueException $e ) {
			return 0;
		}

		$extensions = [
			Html::EXT,
			Gzip::EXT,
			Brotli::EXT,
			Zstd::EXT,
		];

		$seen = [];

		/** @var FilesystemIterator $file */
		foreach ( $iterator as $file ) {
			$dir = $file->getPath();

			// Only count 1 cache extension per directory once.
			if ( isset( $seen[ $dir ] ) ) {
				continue;
			}

			if ( in_array( $file->getExtension(), $extensions, true ) ) {
				$seen[ $dir ] = true;
			}
		}

		return count( $seen );
	}
}
