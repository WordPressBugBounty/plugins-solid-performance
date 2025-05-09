<?php
/**
 * Creates a header instance with HTTP request headers.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Http;

/**
 * Creates a header instance using HTTP request headers.
 *
 * @package SolidWP\Performance
 */
class Request_Header_Factory {

	/**
	 * Make a header instance using HTTP request headers.
	 *
	 * @return Header
	 */
	public function make(): Header {
		$headers = getallheaders();

		return new Header( $headers );
	}
}
