<?php
/**
 * Registers custom cron schedules and scheduled tasks related functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Cron\Tasks\Clean_Expired_Cache_Files_Task;

/**
 * Registers custom cron schedules and scheduled tasks related functionality in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const SCHEDULE_EVERY_30_MINUTES     = 'solid.performance.cron.every_30_minutes';
	public const SCHEDULE_KEY_EVERY_30_MINUTES = 'swpsp_every_30_minutes';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_schedules();
		$this->register_tasks();
	}

	/**
	 * Register custom cron schedules.
	 *
	 * @return void
	 */
	private function register_schedules(): void {
		$this->container->singleton(
			self::SCHEDULE_EVERY_30_MINUTES,
			static fn() => new Schedule(
				self::SCHEDULE_KEY_EVERY_30_MINUTES,
				HOUR_IN_SECONDS / 2,
				__( 'Every 30 minutes', 'solid-performance' )
			)
		);

		$this->container->singleton(
			Schedule_Collection::class,
			fn() => new Schedule_Collection(
				// Register custom cron schedules here.
				$this->container->get( self::SCHEDULE_EVERY_30_MINUTES ),
			)
		);

		add_filter(
			'cron_schedules',
			function ( array $schedules ) {
				$custom_schedules = $this->container->get( Schedule_Collection::class )->to_array();

				return array_merge( $schedules, $custom_schedules );
			},
			10,
			1
		);
	}

	/**
	 * Register scheduled tasks.
	 *
	 * @return void
	 */
	private function register_tasks(): void {
		$this->container->when( Clean_Expired_Cache_Files_Task::class )
						->needs( Schedule::class )
						->give( fn() => $this->container->get( self::SCHEDULE_EVERY_30_MINUTES ) );

		$this->container->singleton(
			Registry::class,
			fn() => new Registry(
				// Register your cronjob tasks here.
				$this->container->get( Clean_Expired_Cache_Files_Task::class ),
			)
		);

		// Register all the scheduled task hooks with WordPress.
		add_action( 'init', fn() => $this->container->get( Scheduler::class )->register_task_hooks() );
	}
}
