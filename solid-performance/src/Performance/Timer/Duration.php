<?php
/**
 * Formats a duration in a human-readable format.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer;

/**
 * Formats a duration in a human-readable format.
 *
 * @package SolidWP\Performance
 */
final class Duration {

	/**
	 * The duration in seconds.
	 *
	 * @var float
	 */
	private float $seconds;

	/**
	 * @param  float $seconds The duration in seconds.
	 */
	public function __construct( float $seconds ) {
		$this->seconds = $seconds;
	}

	/**
	 * Format the duration in human-readable form.
	 *
	 * @return string In the format of: 2 hours, 14 minutes, 9 seconds
	 */
	public function format(): string {
		return human_readable_duration( gmdate( 'H:i:s', (int) $this->seconds ) );
	}
}
