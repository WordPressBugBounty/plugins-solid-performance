<?php
/**
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Contracts;

use SolidWP\Performance\lucatume\DI52\Container;

/**
 * The task factory to create a collection of tasks specified to run at
 * different stages of the request execution.
 *
 * @package SolidWP\Performance
 */
abstract class Abstract_Task_Factory implements Task_Collection_Factory {

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * The list of task class strings that will be created.
	 *
	 * @var class-string<Task>[]
	 */
	private array $tasks;

	/**
	 * @param Container            $container The container.
	 * @param class-string<Task>[] $tasks The list of task class strings that will be created.
	 */
	public function __construct( Container $container, array $tasks ) {
		$this->container = $container;
		$this->tasks     = $tasks;
	}

	/**
	 * Create the task instances.
	 *
	 * Make sure you have specified any container definitions in the Provider that are required
	 * to build these instances ahead of running this.
	 *
	 * @return Task[]
	 */
	public function make(): array {
		$tasks = [];

		foreach ( $this->tasks as $task ) {
			$tasks[] = $this->container->get( $task );
		}

		return $tasks;
	}
}
