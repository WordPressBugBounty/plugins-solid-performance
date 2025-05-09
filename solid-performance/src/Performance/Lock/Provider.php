<?php
/**
 * The Service Provider for atomic locking.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Lock\Contracts\Blockable_Lock;
use SolidWP\Performance\Lock\Drivers\Database_Driver;
use SolidWP\Performance\Lock\Tables\Cache_Lock;

/**
 * The Service Provider for atomic locking.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->when( Database_Driver::class )
						->needs( '$table' )
						->give( fn(): string => Cache_Lock::table_name( false ) );

		// Set up the Lock Driver.
		$this->container->bind( Contracts\Lock::class, Database_Driver::class );
		// Set up the Lock Decorator.
		$this->container->bind( Blockable_Lock::class, Lock::class );
	}
}
