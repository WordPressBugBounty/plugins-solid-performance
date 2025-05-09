<?php
/**
 * The htaccess reader interface.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess\Contracts;

use SolidWP\Performance\Cache_Delivery\Htaccess\Exceptions\HtaccessReadException;

/**
 * @internal
 */
interface Readable {

	/**
	 * Acquire a read lock and read the contents of the .htaccess file.
	 *
	 * @throws HtaccessReadException When we are unable to open or lock the .htaccess file for reading.
	 *
	 * @return string
	 */
	public function read(): string;
}
