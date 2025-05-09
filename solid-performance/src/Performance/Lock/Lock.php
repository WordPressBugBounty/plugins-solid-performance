<?php
/**
 * A lock decorator to store a lock in the underlying driver.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock;

use SolidWP\Performance\Lock\Contracts\Blockable_Lock;
use SolidWP\Performance\Lock\Exceptions\LockTimeoutException;

/**
 * A lock decorator to store a lock in the underlying driver.
 *
 * @package SolidWP\Performance
 */
final class Lock implements Blockable_Lock {

	/**
	 * @var Contracts\Lock
	 */
	private Contracts\Lock $driver;

	/**
	 * The time in milliseconds to wait in-between retrying blocking locks.
	 *
	 * @var int
	 */
	private int $retry_delay;

	/**
	 * @param Contracts\Lock $driver The lock driver.
	 * @param int            $retry_delay The time in milliseconds to wait in-between retrying blocking locks.
	 */
	public function __construct( Contracts\Lock $driver, int $retry_delay = 250 ) {
		$this->driver      = $driver;
		$this->retry_delay = $retry_delay;
	}

	/**
	 * Attempt to acquire a lock.
	 *
	 * @return bool
	 */
	public function acquire(): bool {
		return $this->driver->acquire();
	}

	/**
	 * Determine if this lock is already acquired.
	 *
	 * @return bool
	 */
	public function is_acquired(): bool {
		return $this->driver->is_acquired();
	}

	/**
	 * Attempt to acquire the lock for the given number of seconds.
	 *
	 * @param  int $seconds How long to wait to acquire the lock before giving up.
	 *
	 * @throws LockTimeoutException If we are unable to acquire the lock within the given seconds.
	 *
	 * @return bool
	 */
	public function block( int $seconds ): bool {
		// Convert milliseconds to microseconds.
		$sleep = $this->retry_delay * 1000;
		// Start time in microseconds.
		$start = (int) ( microtime( true ) * 1_000_000 );
		// Convert the wait time to microseconds.
		$microseconds_to_wait = $seconds * 1_000_000;

		while ( ! $this->acquire() ) {
			$now = (int) ( microtime( true ) * 1_000_000 );

			// Elapsed time in microseconds.
			$elapsed = $now - $start;

			if ( $elapsed >= $microseconds_to_wait ) {
				throw new LockTimeoutException();
			}

			usleep( $sleep );
		}

		return true;
	}

	/**
	 * Get the original owner of the lock.
	 *
	 * @return string
	 */
	public function owner(): string {
		return $this->driver->owner();
	}

	/**
	 * Release the lock, if the original owner matches the current one.
	 *
	 * @return bool
	 */
	public function release(): bool {
		return $this->driver->release();
	}

	/**
	 * Force release the lock, regardless of who owns it.
	 *
	 * @return bool
	 */
	public function force_release(): bool {
		return $this->driver->force_release();
	}
}
