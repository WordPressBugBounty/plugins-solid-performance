<?php
/**
 * Monitors System Load.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter\Load;

use RuntimeException;

/**
 * Monitors System Load.
 *
 * @package SolidWP\Performance
 */
class System_Load_Monitor {

	/**
	 * @var System_Load_Fetcher
	 */
	private System_Load_Fetcher $fetcher;

	/**
	 * @param System_Load_Fetcher $fetcher The system load fetcher.
	 */
	public function __construct( System_Load_Fetcher $fetcher ) {
		$this->fetcher = $fetcher;
	}

	/**
	 * Get the load average DTO object.
	 *
	 * @throws RuntimeException If we fail to get the system load average.
	 *
	 * @return Load
	 */
	public function load(): Load {
		$load = $this->fetcher->load_averages();

		return Load::from( $load );
	}
}
