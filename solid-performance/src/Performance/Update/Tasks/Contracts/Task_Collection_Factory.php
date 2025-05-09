<?php
/**
 * Create a collection tasks to be run.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Contracts;

interface Task_Collection_Factory {

	/**
	 * Create a collection of task objects.
	 *
	 * @return Task[]
	 */
	public function make(): array;
}
