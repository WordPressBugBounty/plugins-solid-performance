<?php
/**
 * Creates a header instance with HTTP response headers.
 *
 * @since   1.0.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Http;

/**
 * Creates a header instance using HTTP response headers.
 *
 * @since   1.0.0
 *
 * @package SolidWP\Performance
 */
class Header_Factory {

	/**
	 * Make a header instance using HTTP response headers.
	 *
	 * @note headers_list() does not work in CLI.
	 *
	 * @return Header
	 */
	public function make(): Header {
		$headers = headers_list();

		return new Header( $headers );
	}
}
