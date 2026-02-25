<?php
/**
 * Resolves the current device type visiting the site.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Contracts;

/**
 * Resolves the current device type visiting the site.
 *
 * @package SolidWP\Performance
 */
interface Device_Resolver {

	public const MOBILE_SUFFIX = '-mobile';

	/**
	 * Whether we consider this a mobile device in order to create
	 * a mobile cache file.
	 *
	 * @return bool
	 */
	public function is_mobile(): bool;
}
