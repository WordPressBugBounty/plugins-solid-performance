<?php
/**
 * Register memoization definitions in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Memoize;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\StellarWP\Memoize\Contracts\DriverInterface;
use SolidWP\Performance\StellarWP\Memoize\Contracts\MemoizerInterface;
use SolidWP\Performance\StellarWP\Memoize\Drivers\MemoryDriver;
use SolidWP\Performance\StellarWP\Memoize\Memoizer;

/**
 * Register memoization definitions in the container.
 *
 * @link https://github.com/stellarwp/memoize
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->singleton( DriverInterface::class, MemoryDriver::class );
		$this->container->bind( MemoizerInterface::class, Memoizer::class );
	}
}
