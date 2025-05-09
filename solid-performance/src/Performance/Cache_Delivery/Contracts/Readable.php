<?php
/**
 * The cache delivery reader interface to read different cache delivery config files.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Contracts;

use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;

/**
 * @internal
 */
interface Readable {

	/**
	 * Acquire a read lock and read the contents of a cache delivery file.
	 *
	 * @throws CacheDeliveryReadException When we are unable to open or lock a cache delivery file for reading.
	 *
	 * @return string
	 */
	public function read(): string;
}
