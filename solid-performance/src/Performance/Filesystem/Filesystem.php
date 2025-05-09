<?php
/**
 * The Filesystem component.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Filesystem;

use SolidWP\Performance\Filesystem\Exceptions\IOException;
use SolidWP\Performance\Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use SolidWP\Performance\Symfony\Component\Filesystem\Exception\IOException as SymfonyException;
use Throwable;

/**
 * The Filesystem component.
 *
 * @package SolidWP\Performance
 */
class Filesystem {

	/**
	 * @var SymfonyFilesystem
	 */
	private SymfonyFilesystem $filesystem;

	/**
	 * @param SymfonyFilesystem $filesystem The Symfony filesystem component.
	 */
	public function __construct(
		SymfonyFilesystem $filesystem
	) {
		$this->filesystem = $filesystem;
	}

	/**
	 * Acquire a read lock and read the contents of a file.
	 *
	 * @param string $path The path to the file.
	 *
	 * @throws IOException When we are unable to open or lock the file for reading.
	 *
	 * @return string
	 */
	public function read( string $path ): string {
		// Open in binary mode to ensure consistent behavior by preventing automatic newline conversions.
		$handle = @fopen( $path, 'rb' );

		if ( ! $handle ) {
			throw new IOException(
				sprintf(
					'Failed to open the "%s" file for reading.',
					$path
				),
				0,
				null,
				$path
			);
		}

		try {
			if ( ! flock( $handle, LOCK_SH | LOCK_NB ) ) {
				throw new IOException(
					sprintf(
						'Failed to acquire a shared lock on the "%s" file.',
						$path
					),
					0,
					null,
					$path
				);
			}

			clearstatcache( true, $path );

			$content = stream_get_contents( $handle );

			if ( $content === false ) {
				throw new IOException(
					sprintf(
						'Failed to read content from "%s" file.',
						$path,
					),
					0,
					null,
					$path
				);
			}

			return $content;
		} catch ( Throwable $e ) {
			throw new IOException( $e->getMessage(), $e->getCode(), $e, $path );
		} finally {
			flock( $handle, LOCK_UN );
			fclose( $handle );
		}
	}

	/**
	 * Atomically dumps content into a file.
	 *
	 * @param string          $path The path to the file to write.
	 * @param string|resource $content The data to write to the file.
	 *
	 * @throws IOException If writing to the file fails.
	 */
	public function write( string $path, $content ) {
		try {
			$this->filesystem->dumpFile( $path, $content );
		} catch ( SymfonyException $e ) {
			throw new IOException( $e->getMessage(), $e->getCode(), $e, $e->getPath() );
		}
	}

	/**
	 * Remove files or directories.
	 *
	 * @param string|iterable $paths A path or an array of paths to remove.
	 *
	 * @throws IOException When removal fails.
	 *
	 * @return void
	 */
	public function remove( $paths ) {
		try {
			$this->filesystem->remove( $paths );
		} catch ( SymfonyException $e ) {
			throw new IOException( $e->getMessage(), $e->getCode(), $e, $e->getPath() );
		}
	}
}
