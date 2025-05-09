<?php
/**
 * Provides an API for all classes that are runnable.
 *
 * @since 1.0.0
 *
 * @package SolidWP\Performance\StellarWP\Telemetry\Contracts
 */

namespace SolidWP\Performance\StellarWP\Telemetry\Contracts;

/**
 * Provides an API for all classes that are runnable.
 *
 * @since 1.0.0
 *
 * @package SolidWP\Performance\StellarWP\Telemetry\Contracts
 */
interface Runnable {
	/**
	 * Run the intended action.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run();
}
