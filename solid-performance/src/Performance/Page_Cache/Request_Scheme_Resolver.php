<?php
/**
 * Resolves the scheme of the current request (HTTP or HTTPS).
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache;

use SolidWP\Performance\Http\Request_Header_Factory;

/**
 * Determines whether the current request is using HTTP or HTTPS.
 *
 * This class inspects server variables and request headers to resolve
 * the scheme of the request.
 *
 * @package SolidWP\Performance
 */
final class Request_Scheme_Resolver {

	public const HTTPS = 'https';
	public const HTTP  = 'http';

	/**
	 * @var Request_Header_Factory
	 */
	private Request_Header_Factory $header_factory;

	/**
	 * @param Request_Header_Factory $header_factory The header factory.
	 */
	public function __construct( Request_Header_Factory $header_factory ) {
		$this->header_factory = $header_factory;
	}

	/**
	 * Returns true if this request is using HTTPs.
	 *
	 * @return bool
	 */
	public function is_ssl(): bool {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			// phpcs:ignore
			$https = (string) $_SERVER['HTTPS'];

			if ( strtolower( $https ) === 'on' || $https === '1' ) {
				return true;
			}
		}

		if ( ( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == '443' ) ) {
			return true;
		}

		$headers = $this->header_factory->make();

		if (
			$headers->contains( 'X-Forwarded-Proto', 'https' ) ||
			$headers->contains( 'X-Forwarded-Scheme', 'https' )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Resolves and returns the scheme of the current request.
	 *
	 * @return string 'https' if the request is secure, otherwise 'http'.
	 */
	public function get_scheme(): string {
		return $this->is_ssl() ? self::HTTPS : self::HTTP;
	}
}
