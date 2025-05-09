<?php
/**
 * The htaccess writer interface.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess\Contracts;

/**
 * @internal
 */
interface Writable {

	/**
	 * Write to the .htaccess file.
	 *
	 * @param string $content The content to save to the .htaccess file.
	 *
	 * @return bool
	 */
	public function write( string $content ): bool;
}
