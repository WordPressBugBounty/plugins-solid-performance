<?php
/**
 * Creates a Duration object from the duration in seconds.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer;

use SolidWP\Performance\Container;

/**
 * Creates a Duration object from the duration in seconds.
 *
 * @package SolidWP\Performance
 */
final class Duration_Factory {

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
	 * Make a duration object.
	 *
	 * @param  float $seconds The duration in seconds.
	 *
	 * @return Duration
	 */
	public function make( float $seconds ): Duration {
		$this->container->when( Duration::class )
						->needs( '$seconds' )
						->give( $seconds );

		return $this->container->get( Duration::class );
	}
}
