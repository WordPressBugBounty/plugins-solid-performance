<?php
/**
 * HTTP URI helpers shared with cache path logic.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Http;

/**
 * HTTP URI helpers shared with cache path logic.
 *
 * @package SolidWP\Performance
 */
final class Uri {

	/**
	 * Normalize a hostname for stable comparison and filesystem paths.
	 *
	 * DNS hostnames are case-insensitive (RFC 4343). Trailing root-zone dots are
	 * insignificant (RFC 1035).
	 *
	 * @param string $host The host from parse_url(), $_SERVER['HTTP_HOST'], etc.
	 *
	 * @return string
	 */
	public static function normalize_host( string $host ): string {
		return strtolower( rtrim( $host, '.' ) );
	}
}
