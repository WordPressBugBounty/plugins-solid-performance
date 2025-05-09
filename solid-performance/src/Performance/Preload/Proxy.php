<?php
/**
 * Configures WP Proxies for the Symfony HTTP Client.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use WP_HTTP_Proxy;

/**
 * Configures WP Proxies for the Symfony HTTP Client.
 *
 * @package SolidWP\Performance
 */
final class Proxy {

	/**
	 * @var WP_HTTP_Proxy
	 */
	private WP_HTTP_Proxy $proxy;

	/**
	 * @param WP_HTTP_Proxy $proxy The WP Proxy object.
	 */
	public function __construct( WP_HTTP_Proxy $proxy ) {
		$this->proxy = $proxy;
	}

	/**
	 * Get a configured WP Proxy URL.
	 *
	 * @example username:password@192.168.12.14:8080
	 *
	 * @return string
	 */
	public function url(): string {
		if ( ! $this->proxy->is_enabled() ) {
			return '';
		}

		$parts = [];

		if ( $this->proxy->use_authentication() ) {
			$parts[] = $this->proxy->authentication();
			$parts[] = '@';
		}

		$parts[] = $this->proxy->host();

		$port = $this->proxy->port();

		if ( strlen( $port ) ) {
			$parts[] = ':' . $port;
		}

		return implode( '', $parts );
	}
}
