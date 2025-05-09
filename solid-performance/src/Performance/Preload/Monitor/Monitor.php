<?php
/**
 * The preload monitor to check for a stalled preloader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Monitor;

use SolidWP\Performance\Preload\Monitor\Exceptions\PreloadMonitorMaxRetriesException;

/**
 * The preload monitor to check for a stalled preloader.
 *
 * @package SolidWP\Performance
 */
class Monitor {

	/**
	 * @var Repository
	 */
	private Repository $repository;

	/**
	 * How long in seconds the count can remain stale for.
	 *
	 * @var int
	 */
	private int $timeout;

	/**
	 * The maximum number of times we retry a stalled preloader before giving up.
	 *
	 * @var int
	 */
	private int $max_retries;

	/**
	 * @param Repository $repository The monitor repository.
	 * @param int        $timeout How long in seconds the count can remain stale for.
	 * @param int        $max_retries The maximum number of times we retry a stalled preloader before giving up.
	 */
	public function __construct( Repository $repository, int $timeout = 12, int $max_retries = 10 ) {
		$this->repository  = $repository;
		$this->timeout     = $timeout;
		$this->max_retries = $max_retries;
	}

	/**
	 * Check if the preloader is stalled out.
	 *
	 * @param int $url_count The number of URLs remaining.
	 *
	 * @throws PreloadMonitorMaxRetriesException If we exhausted all of our retries.
	 *
	 * @return bool
	 */
	public function is_stalled( int $url_count ): bool {
		$status        = $this->repository->get();
		$count         = $status['count'] ?? 0;
		$last_activity = $status['last_activity'] ?? 0;
		$retries       = $status['retries'] ?? 0;

		// This is the first run.
		if ( ! $count && ! $last_activity ) {
			$this->repository->set( $url_count );

			return false;
		}

		// Only check every x seconds...
		if ( time() - $last_activity < $this->timeout ) {
			return false;
		}

		// URL count has not changed, we're stalled.
		$is_stalled = $count === $url_count;

		// Check if we've reached the max retries.
		if ( $is_stalled ) {
			++$retries;

			if ( $retries >= $this->max_retries ) {
				throw new PreloadMonitorMaxRetriesException(
					sprintf(
						'The Preload Monitor tried to restart the preloader %d times and gave up.',
						$this->max_retries
					)
				);
			}
		}

		$this->repository->set( $url_count, $retries );

		return $is_stalled;
	}

	/**
	 * Clean up any monitor data.
	 *
	 * @return bool
	 */
	public function clean(): bool {
		return $this->repository->delete();
	}
}
