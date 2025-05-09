<?php
/**
 * Transform URIs.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use SolidWP\Performance\Symfony\Component\HttpClient\HttpClientTrait;

/**
 * Transform URIs.
 *
 * @package SolidWP\Performance
 */
final class Uri {

	use HttpClientTrait;

	/**
	 * Ensure we pass a relative URL to our Scoped HTTP client, which is configured
	 * to use the WP Home URL as the base_uri in order to prevent SSRF attacks.
	 *
	 * @param string $uri The URI that will be forced to a relative path.
	 *
	 * @throws InvalidArgumentException If the URL is malformed.
	 *
	 * @return string
	 */
	public function make_relative( string $uri ): string {
		$uri              = self::parseUrl( $uri );
		$uri['scheme']    = '';
		$uri['authority'] = '';

		$uri = implode( '', $uri );

		return '/' . ltrim( $uri, '/\\' );
	}
}
