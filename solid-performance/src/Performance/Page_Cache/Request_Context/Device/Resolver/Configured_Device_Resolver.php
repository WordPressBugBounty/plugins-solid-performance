<?php
/**
 * A decorator to see if Mobile Caching is enabled or not before
 * resolving devices.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver;

use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Contracts\Device_Resolver;

/**
 * A decorator to see if Mobile Caching is enabled or not before
 * resolving devices.
 *
 * @package SolidWP\Performance
 */
final class Configured_Device_Resolver implements Device_Resolver {

	/**
	 * @var Device_Resolver
	 */
	private Device_Resolver $device_resolver;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param  Device_Resolver $device_resolver The underlying resolver.
	 * @param  Config          $config  The config object.
	 */
	public function __construct(
		Device_Resolver $device_resolver,
		Config $config
	) {
		$this->device_resolver = $device_resolver;
		$this->config          = $config;
	}

	/**
	 * If enabled, pass down to the underlying resolver, otherwise
	 * we consider everything "desktop".
	 *
	 * @return bool
	 */
	public function is_mobile(): bool {
		$enabled = (bool) $this->config->get( 'page_cache.mobile_cache.enabled' );

		if ( $enabled ) {
			return $this->device_resolver->is_mobile();
		}

		return false;
	}
}
