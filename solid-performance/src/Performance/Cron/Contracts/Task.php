<?php
/**
 * The scheduled task contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron\Contracts;

/**
 * The scheduled task contract.
 *
 * @package SolidWP\Performance
 */
interface Task {

	/**
	 * Run the task when the scheduler executes the task.
	 *
	 * @return mixed
	 */
	public function run();

	/**
	 * The unique hook name to register.
	 *
	 * @return string
	 */
	public function hook(): string;

	/**
	 * How often the event should subsequently recur.
	 *
	 * @see wp_get_schedules() for accepted values.
	 *
	 * @return string
	 */
	public function recurrence(): string;
}
