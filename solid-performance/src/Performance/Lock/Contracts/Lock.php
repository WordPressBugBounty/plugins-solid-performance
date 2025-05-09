<?php
/**
 * The Lock contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock\Contracts;

/**
 * The Lock contract.
 *
 * @internal
 *
 * @package SolidWP\Performance
 */
interface Lock {

	/**
	 * Attempt to acquire a lock.
	 *
	 * @return bool
	 */
	public function acquire(): bool;

	/**
	 * Determine if this lock is already acquired.
	 *
	 * @return bool
	 */
	public function is_acquired(): bool;

	/**
	 * Get the original owner of the lock.
	 *
	 * @return string
	 */
	public function owner(): string;

	/**
	 * Release the lock, if the original owner matches the current one.
	 *
	 * @return bool
	 */
	public function release(): bool;

	/**
	 * Force release the lock, regardless of who owns it.
	 *
	 * @return bool
	 */
	public function force_release(): bool;
}
