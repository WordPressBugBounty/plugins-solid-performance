<?php
/**
 * Automatically detect the current device.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context\Device;

use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Contracts\Device_Resolver;

/**
 * Automatically detect the current device.
 *
 * @package SolidWP\Performance
 */
final class Auto_Device_Context implements Contracts\Device_Context {

	/**
	 * @var Device_Resolver
	 */
	private Device_Resolver $resolver;

	/**
	 * @param  Device_Resolver $resolver The device resolver.
	 */
	public function __construct(
		Device_Resolver $resolver
	) {
		$this->resolver = $resolver;
	}

	/**
	 * @inheritDoc
	 */
	public function suffix(): string {
		return $this->resolver->is_mobile()
			? Device_Resolver::MOBILE_SUFFIX
			: '';
	}
}
