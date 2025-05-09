<?php
/**
 * The Batch Size Rate Limiter.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter;

use InvalidArgumentException;
use RuntimeException;
use SolidWP\Performance\Preload\Limiter\Load\System_Load_Monitor;
use SolidWP\Performance\Preload\Preload_Mode_Manager;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * Determines the server load per core to determine how much we should
 * lower the batch size by.
 *
 * @package SolidWP\Performance
 */
class Batch_Limiter {

	public const MIN_BATCH_SIZE = 1;

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
	 * @var Preload_Mode_Manager
	 */
	private Preload_Mode_Manager $preload_mode_manager;

	/**
	 * The original batch size to use.
	 *
	 * @var int
	 */
	private int $batch_size;

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
	 * The negative value used to calculate exponential decay.
	 *
	 * @var float
	 */
	private float $decay;

	/**
	 * @param System_Load_Monitor  $load_monitor The system load monitor.
	 * @param Core_Counter         $core_counter The core counter.
	 * @param LoggerInterface      $logger The logger.
	 * @param Preload_Mode_Manager $preload_mode_manager The preload mode manager.
	 * @param int                  $batch_size The original batch size to use.
	 * @param float                $max_load_per_core The maximum load per core before we start rate limiting.
	 * @param float                $decay The negative value used to calculate exponential decay.
	 *
	 * @throws InvalidArgumentException If invalid ranges are passed.
	 */
	public function __construct(
		System_Load_Monitor $load_monitor,
		Core_Counter $core_counter,
		LoggerInterface $logger,
		Preload_Mode_Manager $preload_mode_manager,
		int $batch_size = 50,
		float $max_load_per_core = 0.5,
		float $decay = -0.4
	) {
		if ( $max_load_per_core < 0.1 ) {
			throw new InvalidArgumentException( 'The $max_load_per_core argument cannot be below 0.1' );
		}

		if ( $decay >= 0.0 ) {
			throw new InvalidArgumentException( 'The $decay argument must be negative' );
		}

		$this->load_monitor         = $load_monitor;
		$this->core_counter         = $core_counter;
		$this->logger               = $logger;
		$this->preload_mode_manager = $preload_mode_manager;
		$this->batch_size           = $batch_size;
		$this->max_load_per_core    = $max_load_per_core;
		$this->decay                = $decay;
	}

	/**
	 * Adjust the new batch size based on the server load.
	 *
	 * @return int
	 */
	public function get_batch_size(): int {
		if ( ! $this->preload_mode_manager->is_high_performance_mode() ) {
			return self::MIN_BATCH_SIZE;
		}

		try {
			$one_min_load = $this->load_monitor->load()->one_min();
		} catch ( RuntimeException $e ) {
			$this->logger->error(
				'Falling back to minimum batch size {minimum_batch_size}',
				[
					'exception'          => $e,
					'minimum_batch_size' => self::MIN_BATCH_SIZE,
				]
			);

			// Fall back to the lowest batch size if we can't determine the load.
			return self::MIN_BATCH_SIZE;
		}

		$core_count = $this->core_counter->core_count();

		// Calculate the threshold load.
		$threshold = $this->max_load_per_core * $core_count;

		// Calculate the load factor (how much the load exceeds the threshold).
		$load_factor = max( 0, ( $one_min_load - $threshold ) / $threshold );

		// Apply exponential decay to calculate the adjusted batch size.
		$batch_size = $this->batch_size * exp( $this->decay * $load_factor );

		$this->logger->debug(
			'Calculated batch size: {batch_size}',
			[
				'batch_size' => $batch_size,
			]
		);

		return (int) max( self::MIN_BATCH_SIZE, round( $batch_size ) );
	}
}
