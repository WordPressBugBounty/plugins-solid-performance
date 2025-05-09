<?php
/**
 * The cron task scheduler.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron;

use SolidWP\Performance\Psr\Log\LoggerInterface;
use WP_Error;

/**
 * The cron task scheduler.
 *
 * @package SolidWP\Performance
 */
final class Scheduler {

	/**
	 * @var Registry
	 */
	private Registry $registry;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Registry        $registry The task registry.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct( Registry $registry, LoggerInterface $logger ) {
		$this->registry = $registry;
		$this->logger   = $logger;
	}

	/**
	 * Schedule all tasks with WordPress.
	 *
	 * @return void
	 */
	public function enable_tasks(): void {
		foreach ( $this->registry->all() as $task ) {
			if ( ! wp_next_scheduled( $task->hook() ) ) {
				wp_schedule_event( time(), $task->recurrence(), $task->hook() );
			}
		}
	}

	/**
	 * Clear all registered tasks.
	 *
	 * @return void
	 */
	public function disable_tasks(): void {
		foreach ( $this->registry->all() as $task ) {
			$result = wp_clear_scheduled_hook( $task->hook(), [], true );

			if ( $result instanceof WP_Error ) {
				$this->logger->error(
					'Error clearing scheduled hook: {message}',
					[
						'message'  => $result->get_error_message(),
						'wp_error' => $result,
					]
				);
			}
		}
	}

	/**
	 * Register all task hooks from the registry.
	 *
	 * @return void
	 */
	public function register_task_hooks(): void {
		foreach ( $this->registry->all() as $task ) {
			if ( has_action( $task->hook() ) ) {
				continue;
			}

			add_action(
				$task->hook(),
				static fn() => $task->run(),
				10,
				0
			);
		}
	}
}
