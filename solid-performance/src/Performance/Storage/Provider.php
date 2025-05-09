<?php
/**
 * Register Storage related definitions in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Storage;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Storage\Contracts\Storage;
use SolidWP\Performance\Storage\Drivers\Option_Storage;

/**
 * Register Storage related definitions in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->singleton( Storage::class, fn(): Storage => $this->container->get( Option_Storage::class ) );
	}
}
