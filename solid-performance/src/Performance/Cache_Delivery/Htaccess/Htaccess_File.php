<?php
/**
 * Represents an .htaccess file.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;

/**
 * Represents an .htaccess file.
 *
 * @package SolidWP\Performance
 */
class Htaccess_File {

	/**
	 * Memoization cache for the file path.
	 *
	 * @var string|null
	 */
	private ?string $filepath = null;

	/**
	 * Get the server path to the .htaccess file.
	 *
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return string
	 */
	public function get_file_path(): string {
		if ( $this->filepath !== null ) {
			return $this->filepath;
		}

		$home_path = swpsp_get_document_root();

		$this->filepath = $home_path . '.htaccess';

		return $this->filepath;
	}

	/**
	 * Check if the .htaccess file exists.
	 *
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		return file_exists( $this->get_file_path() );
	}

	/**
	 * Check if the .htaccess file exists and is writable.
	 *
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return bool
	 */
	public function is_writable(): bool {
		return is_writable( $this->get_file_path() );
	}
}
