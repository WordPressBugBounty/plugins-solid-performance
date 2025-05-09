<?php
/**
 * The Task registry.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron;

use SolidWP\Performance\Cron\Contracts\Task;

/**
 * The Task registry.
 *
 * @package SolidWP\Performance
 */
final class Registry {

	/**
	 * @var Task[]
	 */
	private array $tasks;

	/**
	 * @param Task ...$tasks The tasks to register.
	 */
	public function __construct( Task ...$tasks ) {
		$this->tasks = $tasks;
	}

	/**
	 * Get all tasks.
	 *
	 * @return Task[]
	 */
	public function all(): array {
		return $this->tasks;
	}
}
