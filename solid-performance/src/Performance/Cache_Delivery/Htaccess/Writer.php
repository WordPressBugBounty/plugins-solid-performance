<?php
/**
 * The htaccess writer.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Htaccess\Contracts\Writable;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\Filesystem\Filesystem;

/**
 * The htaccess writer.
 *
 * @package SolidWP\Performance
 */
final class Writer implements Writable {

	/**
	 * @var Htaccess_File
	 */
	private Htaccess_File $htaccess;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Htaccess_File   $htaccess The htaccess object.
	 * @param Filesystem      $filesystem The filesystem object.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct(
		Htaccess_File $htaccess,
		Filesystem $filesystem,
		LoggerInterface $logger
	) {
		$this->htaccess   = $htaccess;
		$this->filesystem = $filesystem;
		$this->logger     = $logger;
	}

	/**
	 * Atomically write content to the .htaccess file.
	 *
	 * @param string $content The content to save to the .htaccess file.
	 *
	 * @return bool
	 */
	public function write( string $content ): bool {
		try {
			$filepath = $this->htaccess->get_file_path();
			$this->filesystem->dumpFile( $filepath, $content );
		} catch ( RuntimeException $e ) {
			$this->logger->error(
				'Unable to write content to .htaccess file',
				[
					'exception' => $e,
				]
			);

			return false;
		}

		return true;
	}
}
