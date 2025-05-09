<?php
/**
 * Deletes cache files that are past their expiration time.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Purge;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SolidWP\Performance\Page_Cache\Cache_Path;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Brotli;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Gzip;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Html;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Zstd;
use SolidWP\Performance\Page_Cache\Expiration;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\Filesystem\Exception\IOException;
use SolidWP\Performance\Symfony\Component\Filesystem\Filesystem;
use UnexpectedValueException;

/**
 * Deletes cache files that are past their expiration time.
 *
 * @package SolidWP\Performance
 */
final class Expired_Cache_Purger {

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @var Expiration
	 */
	private Expiration $expiration;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Cache_Path      $cache_path The cache path object.
	 * @param Expiration      $expiration The expiration object.
	 * @param Filesystem      $filesystem The Symfony filesystem.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct(
		Cache_Path $cache_path,
		Expiration $expiration,
		Filesystem $filesystem,
		LoggerInterface $logger
	) {
		$this->cache_path = $cache_path;
		$this->expiration = $expiration;
		$this->filesystem = $filesystem;
		$this->logger     = $logger;
	}

	/**
	 * Purge expired cache files.
	 *
	 * @return int The number of directories purged.
	 */
	public function purge(): int {
		$deleted_count = 0;
		$expiration    = $this->expiration->get_expiration_length();
		$path          = $this->cache_path->get_site_cache_dir();
		$now           = time();

		try {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS )
			);
		} catch ( UnexpectedValueException $e ) {
			return $deleted_count;
		}

		$extensions = [
			Html::EXT,
			Gzip::EXT,
			Brotli::EXT,
			Zstd::EXT,
			'php', // for .meta.php files.
		];

		/** @var FilesystemIterator $file */
		foreach ( $iterator as $file ) {
			if ( in_array( $file->getExtension(), $extensions, true ) && ( $now - $file->getMTime() ) > $expiration ) {
				if ( $file->getExtension() === 'php' && ! str_ends_with( $file->getFilename(), '.meta.php' ) ) {
					$this->logger->warning(
						'PHP cache file found that is not a meta file: {filename}',
						[
							'filename' => $file->getFilename(),
							'path'     => $file->getPathname(),
						]
					);

					continue;
				}

				try {
					$this->filesystem->remove( $file->getPathname() );
					++$deleted_count;
				} catch ( IOException $e ) {
					$this->logger->error(
						'Unable to delete cache file: {path}',
						[
							'path'      => $e->getPath(),
							'exception' => $e,
						]
					);
				}
			}
		}

		return $deleted_count;
	}
}
