<?php
/**
 * The Lock Data Transfer Object that is stored and retrieved to determine
 * lock state.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock;

/**
 * The Lock Data Transfer Object that is stored and retrieved to determine
 * lock state.
 *
 * @package SolidWP\Performance
 */
final class Lock_Entry {

	/**
	 * The unique lock name.
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * The expiration in seconds.
	 *
	 * @var int
	 */
	private int $expiration;

	/**
	 * The owner of the lock.
	 *
	 * @var string
	 */
	private string $owner;

	/**
	 * @param  string $name        The unique lock name.
	 * @param  int    $expiration  The expiration in seconds.
	 * @param  string $owner       The owner of the lock.
	 */
	public function __construct( string $name, int $expiration, string $owner ) {
		$this->name       = $name;
		$this->expiration = $expiration;
		$this->owner      = $owner;
	}

	/**
	 * Get the lock name.
	 *
	 * @return string
	 */
	public function name(): string {
		return $this->name;
	}

	/**
	 * Get the lock expiration in seconds.
	 *
	 * @return int
	 */
	public function expiration(): int {
		return $this->expiration;
	}

	/**
	 * Get the lock owner.
	 *
	 * @return string
	 */
	public function owner(): string {
		return $this->owner;
	}
}
