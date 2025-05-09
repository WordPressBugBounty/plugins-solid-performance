<?php
/**
 * Counts system CPU cores.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter;

/**
 * Counts system CPU cores.
 *
 * @package SolidWP\Performance
 */
class Core_Counter {

	/**
	 * Memoization cache for the server core count.
	 *
	 * @var int|null
	 */
	private ?int $core_count;

	/**
	 * Get the system CPU core count.
	 *
	 * @return int
	 */
	public function core_count(): int {
		if ( isset( $this->core_count ) ) {
			return $this->core_count;
		}

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$this->core_count = substr_count( (string) @file_get_contents( '/proc/cpuinfo' ), "\nprocessor" ) + 1;

		return $this->core_count;
	}
}
