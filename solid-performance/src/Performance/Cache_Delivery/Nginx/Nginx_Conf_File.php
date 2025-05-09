<?php
/**
 * Represents our nginx.conf file.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Contracts\Config_File;

/**
 * Represents our nginx.conf file.
 *
 * @package SolidWP\Performance
 */
class Nginx_Conf_File extends Config_File {

	/**
	 * Get the server path to the nginx.conf file.
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

		$this->filepath = $home_path . 'swpsp-nginx.conf';

		return $this->filepath;
	}
}
