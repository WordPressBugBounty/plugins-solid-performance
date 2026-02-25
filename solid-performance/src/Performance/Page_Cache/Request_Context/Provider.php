<?php
/**
 * The Service Provider for request context.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Auto_Device_Context;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Contracts\Device_Context;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Configured_Device_Resolver;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Contracts\Device_Resolver;
use SolidWP\Performance\Page_Cache\Request_Context\Device\Resolver\Default_Device_Resolver;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Service Provider for request context.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->bindDecorators(
			Device_Resolver::class,
			[
				Configured_Device_Resolver::class,
				Default_Device_Resolver::class,
			]
		);

		$this->container->bind(
			Device_Context::class,
			Auto_Device_Context::class
		);
	}
}
