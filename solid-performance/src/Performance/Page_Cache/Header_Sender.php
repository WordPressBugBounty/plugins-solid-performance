<?php
/**
 * Sends HTTP response headers when serving cache files.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache;

use SolidWP\Performance\Http\Header;

/**
 * Sends HTTP response headers when serving cache files.
 *
 * @package SolidWP\Performance
 */
class Header_Sender {

	/**
	 * Send HTTP response headers.
	 *
	 * @param Header $header The header object with the cached response headers.
	 *
	 * @return void
	 */
	public function send( Header $header ): void {
		foreach ( $header->all() as $name => $headers ) {
			$header_value = $header->get( $name );

			// Single header value, replace any existing.
			if ( count( $headers ) < 2 ) {
				$this->send_header( "$name: $header_value" );

				continue;
			}

			// Header has multiple values, set multiple values for the same header.
			foreach ( $headers as $value ) {
				$this->send_header( "$name: $value", false );
			}
		}
	}

	/**
	 * Send the HTTP response header.
	 *
	 * @param string $raw_header The raw header in Name: Value format.
	 * @param bool   $replace Whether to replace a previously similar header.
	 *
	 * @return void
	 */
	protected function send_header( string $raw_header, bool $replace = true ): void {
		header( $raw_header, $replace );
	}
}
