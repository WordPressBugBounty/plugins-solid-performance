<?php
/**
 * The swpsp-nginx.conf reader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;
use SolidWP\Performance\Filesystem\Filesystem;

/**
 * The swpsp-nginx.conf reader.
 *
 * @package SolidWP\Performance
 */
final class Reader implements Readable {

	/**
	 * @var Nginx_Conf_File
	 */
	private Nginx_Conf_File $nginx_conf;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @param Nginx_Conf_File $nginx_conf The nginx.conf file object.
	 * @param Filesystem      $filesystem The filesystem component.
	 */
	public function __construct(
		Nginx_Conf_File $nginx_conf,
		Filesystem $filesystem
	) {
		$this->nginx_conf = $nginx_conf;
		$this->filesystem = $filesystem;
	}

	/**
	 * Acquire a read lock and read the contents of the swpsp-nginx.conf file.
	 *
	 * @throws CacheDeliveryReadException If we are unable to open or lock the swpsp-nginx.conf file for reading.
	 *
	 * @return string
	 */
	public function read(): string {
		try {
			if ( ! $this->nginx_conf->exists() ) {
				return '';
			}

			return $this->filesystem->read( $this->nginx_conf->get_file_path() );
		} catch ( RuntimeException $e ) {
			throw new CacheDeliveryReadException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
