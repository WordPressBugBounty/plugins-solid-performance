<?php
/**
 * The swpsp-nginx.conf writer.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Contracts\Writable;
use SolidWP\Performance\Filesystem\Filesystem;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * The swpsp-nginx.conf writer.
 *
 * @package SolidWP\Performance
 */
final class Writer implements Writable {

	/**
	 * @var Nginx_Conf_File
	 */
	private Nginx_Conf_File $nginx_conf;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Nginx_Conf_File $nginx_conf The nginx conf object.
	 * @param Filesystem      $filesystem The filesystem component.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct(
		Nginx_Conf_File $nginx_conf,
		Filesystem $filesystem,
		LoggerInterface $logger
	) {
		$this->nginx_conf = $nginx_conf;
		$this->filesystem = $filesystem;
		$this->logger     = $logger;
	}

	/**
	 * Atomically write content to the swpsp-nginx.conf file.
	 *
	 * @param string $content The content to save to the swpsp-nginx.conf file.
	 *
	 * @return bool
	 */
	public function write( string $content ): bool {
		try {
			$filepath = $this->nginx_conf->get_file_path();
			$this->filesystem->write( $filepath, $content );
		} catch ( RuntimeException $e ) {
			$this->logger->error(
				'Unable to write content to swpsp-nginx.conf file',
				[
					'exception' => $e,
				]
			);

			return false;
		}

		return true;
	}
}
