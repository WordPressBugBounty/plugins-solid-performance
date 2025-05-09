<?php
/**
 * A factory to create an atomic lock instance.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock;

use SolidWP\Performance\Container;
use SolidWP\Performance\Lock\Contracts\Blockable_Lock;
use SolidWP\Performance\Lock\Contracts\Lock;

/**
 * A factory to create an atomic lock instance.
 *
 * @package SolidWP\Performance
 */
final class Lock_Factory {

	public const RANDOM_OWNER_LENGTH = 16;

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * @param  Container $container The container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Make an atomic lock instance.
	 *
	 * @param  string $name        The unique lock name.
	 * @param  int    $expiration  The expiration in seconds.
	 * @param  string $owner       The owner of the lock.
	 *
	 * @return Blockable_Lock
	 */
	public function make( string $name, int $expiration, string $owner = '' ): Blockable_Lock {
		if ( ! strlen( $owner ) ) {
			$owner = wp_generate_password( self::RANDOM_OWNER_LENGTH, false );
		}

		$entry = new Lock_Entry( $name, $expiration, $owner );

		// Configure the Lock Driver to accept the entry.
		$this->container->when( Lock::class )
						->needs( Lock_Entry::class )
						->give( $entry );

		return $this->container->get( Blockable_Lock::class );
	}
}
