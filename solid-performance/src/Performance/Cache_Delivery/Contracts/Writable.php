<?php
/**
 * The cache delivery writer interface to write to different cache delivery config files.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Contracts;

/**
 * @internal
 */
interface Writable {

	/**
	 * Write to the cache delivery configuration file.
	 *
	 * @param string $content The content to save to the file.
	 *
	 * @return bool
	 */
	public function write( string $content ): bool;
}
