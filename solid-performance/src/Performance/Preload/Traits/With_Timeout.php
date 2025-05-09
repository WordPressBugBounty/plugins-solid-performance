<?php
/**
 * Timeout related shared HTTP request functionality.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Traits;

trait With_Timeout {

	/**
	 * Get the Preload HTTP request timeout.
	 *
	 * @return float
	 */
	private function get_request_timeout(): float {
		global $is_apache;

		$timeout  = 0.1;
		$is_debug = defined( 'WP_DEBUG' ) && WP_DEBUG;

		// Set Apache timeout higher when running WP_DEBUG, so that the debug.log is properly written to.
		if ( $is_apache && $is_debug ) {
			$timeout = 59.0;
		}

		/**
		 * Filter the Preload request timeout.
		 *
		 * @param float $timeout The request timeout as a float/double.
		 * @param bool $is_apache Whether we are running on Apache.
		 * @param bool $is_debug Whether WP_DEBUG is enabled.
		 */
		return apply_filters( 'solidwp/performance/preload/request_timeout', $timeout, $is_apache, $is_debug );
	}
}
