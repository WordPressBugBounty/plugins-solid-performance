<?php
/**
 * The Sleep Rate Limiter.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter;

use InvalidArgumentException;
use RuntimeException;
use SolidWP\Performance\Preload\Limiter\Load\System_Load_Monitor;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * Determines the server load per core to determine the time
 * to sleep in between batches.
 *
 * Sleeping lowers load faster than lowering batch sizes.
 *
 * @package SolidWP\Performance
 */
class Sleep_Limiter {

	public const MAX_SLEEP = 10.0;

	public const FALLBACK_SLEEP = 3.0;

	/**
	 * @var System_Load_Monitor
	 */
	private System_Load_Monitor $load_monitor;

	/**
	 * @var Core_Counter
	 */
	private Core_Counter $core_counter;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * The maximum server load per core before we
	 * start rate limiting.
	 *
	 * 0-0.7 per core is optimal range, allowing headroom.
	 * 0.7-1.0 per core is high, but manageable.
	 * 1.0+ per core indicates CPU is likely bottle necked.
	 *
	 * @var float
	 */
	private float $max_load_per_core;

	/**
	 * The delay multiplier to calculate the sleep time.
	 *
	 * @var float
	 */
	private float $delay;

	/**
	 * @param System_Load_Monitor $load_monitor The system load monitor.
	 * @param Core_Counter        $core_counter The core counter.
	 * @param LoggerInterface     $logger The logger.
	 * @param float               $max_load_per_core The maximum load per core before we start rate limiting.
	 * @param float               $delay The delay multiplier to calculate the sleep time.
	 *
	 * @throws InvalidArgumentException If an invalid max_load_per_core or sleep argument is passed.
	 */
	public function __construct(
		System_Load_Monitor $load_monitor,
		Core_Counter $core_counter,
		LoggerInterface $logger,
		float $max_load_per_core = 0.5,
		float $delay = 1.5
	) {
		if ( $max_load_per_core < 0.1 ) {
			throw new InvalidArgumentException( 'The $max_load_per_core argument cannot be below 0.1' );
		}

		if ( $delay < 0.1 ) {
			throw new InvalidArgumentException( 'The $delay argument cannot be below 0.1' );
		}

		$this->load_monitor      = $load_monitor;
		$this->core_counter      = $core_counter;
		$this->logger            = $logger;
		$this->max_load_per_core = $max_load_per_core;
		$this->delay             = $delay;
	}

	/**
	 * Get the seconds to sleep.
	 *
	 * Formula:
	 *  sleep_time = delay * (load_per_core / max_load_per_core) ^ alpha.
	 *
	 * @return float
	 */
	public function get_sleep(): float {
		try {
			$one_min_load = $this->load_monitor->load()->one_min();
		} catch ( RuntimeException $e ) {
			$this->logger->error(
				'Falling back to default sleep: {fallback_sleep}',
				[
					'exception'      => $e,
					'fallback_sleep' => self::FALLBACK_SLEEP,
				]
			);

			// Fall back to a reasonable sleep if we can't determine server load.
			return self::FALLBACK_SLEEP;
		}

		$core_count = $this->core_counter->core_count();

		$load_per_core = $one_min_load / $core_count;

		if ( $load_per_core <= $this->max_load_per_core ) {
			return 0.0;
		}

		// Exponentially scale sleep based on load per core.
		$sleep = $this->delay * pow( $load_per_core / $this->max_load_per_core, 2.0 );

		$this->logger->debug(
			'Calculated Sleep: {sleep}',
			[
				'sleep' => $sleep,
			]
		);

		return min( self::MAX_SLEEP, $sleep );
	}
}
