<?php
/**
 * The Lock contract that allows block acquisition.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock\Contracts;

use SolidWP\Performance\Lock\Exceptions\LockTimeoutException;

/**
 * The Lock contract that allows block acquisition.
 *
 * @internal
 *
 * @package SolidWP\Performance
 */
interface Blockable_Lock extends Lock {

	/**
	 * Attempt to acquire the lock for the given number of seconds.
	 *
	 * @param  int $seconds How long to wait to acquire the lock before giving up.
	 *
	 * @throws LockTimeoutException If we are unable to acquire the lock within the given seconds.
	 *
	 * @return bool
	 */
	public function block( int $seconds ): bool;
}
