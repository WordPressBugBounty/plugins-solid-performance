<?php
/**
 * A wrapper to get system load averages.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter\Load;

use RuntimeException;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * A wrapper to get system load averages.
 *
 * @package SolidWP\Performance
 */
class System_Load_Fetcher {

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get the system load averages.
	 *
	 * @throws RuntimeException If we fail to get the system load average.
	 *
	 * @return float[]
	 */
	public function load_averages(): array {
		if ( ! function_exists( 'sys_getloadavg' ) ) {
			throw new RuntimeException( 'The sys_getloadavg() function is not enabled.' );
		}

		$load = sys_getloadavg();

		if ( $load === false ) {
			throw new RuntimeException( 'Unable to retrieve system load averages.' );
		}

		$this->logger->debug( 'Raw system load', $load );

		return $load;
	}
}
