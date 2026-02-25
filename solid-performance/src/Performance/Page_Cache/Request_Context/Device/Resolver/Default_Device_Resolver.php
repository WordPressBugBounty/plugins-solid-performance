<?php
/**
 * Determines what type of device is viewing the site right now.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver;

use SolidWP\Performance\Detection\MobileDetect;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Contracts\Device_Resolver;

/**
 * Determines what type of device is viewing the site right now.
 *
 * @package SolidWP\Performance
 */
final class Default_Device_Resolver implements Device_Resolver {

	/**
	 * @var MobileDetect
	 */
	private MobileDetect $detect;

	/**
	 * @param  MobileDetect $detect  The Mobile Detect Library.
	 */
	public function __construct(
		MobileDetect $detect
	) {
		$this->detect = $detect;
	}

	/**
	 * Whether this is a mobile device.
	 *
	 * @return bool
	 */
	public function is_mobile(): bool {
		return $this->detect->isMobile() && ! $this->detect->isTablet();
	}
}
