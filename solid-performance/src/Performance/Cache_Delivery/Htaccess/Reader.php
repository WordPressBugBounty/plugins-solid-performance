<?php
/**
 * The .htaccess reader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Htaccess\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Htaccess\Exceptions\HtaccessReadException;

/**
 * The .htaccess reader.
 *
 * @package SolidWP\Performance
 */
final class Reader implements Readable {

	/**
	 * @var Htaccess_File
	 */
	private Htaccess_File $htaccess;

	/**
	 * @param Htaccess_File $htaccess The htaccess file object.
	 */
	public function __construct( Htaccess_File $htaccess ) {
		$this->htaccess = $htaccess;
	}

	/**
	 * Acquire a read lock and read the contents of the .htaccess file.
	 *
	 * @throws HtaccessReadException When we are unable to open or lock the .htaccess file for reading.
	 *
	 * @return string
	 */
	public function read(): string {
		try {
			if ( ! $this->htaccess->exists() ) {
				return '';
			}

			$path    = $this->htaccess->get_file_path();
			$content = '';

			// Open in binary mode to ensure consistent behavior by preventing automatic newline conversions.
			$handle = fopen( $path, 'rb' );

			if ( ! $handle ) {
				throw new HtaccessReadException( 'Failed to open the .htaccess file for reading.' );
			}

			try {
				if ( ! flock( $handle, LOCK_SH | LOCK_NB ) ) {
					throw new HtaccessReadException( 'Failed to acquire a shared lock on the .htaccess file.' );
				}

				clearstatcache( true, $path );

				$content = stream_get_contents( $handle );

				flock( $handle, LOCK_UN );
			} finally {
				fclose( $handle );
			}

			return $content;
		} catch ( RuntimeException $e ) {
			throw new HtaccessReadException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
