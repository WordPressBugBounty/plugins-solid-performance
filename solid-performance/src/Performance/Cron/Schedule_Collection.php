<?php
/**
 * Holds a collection of schedule objects.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron;

/**
 * Holds a collection of schedule objects.
 *
 * @package SolidWP\Performance
 */
final class Schedule_Collection {

	/**
	 * @var array<string, Schedule>
	 */
	private array $schedules;

	/**
	 * @param Schedule ...$schedules The schedule objects.
	 */
	public function __construct( Schedule ...$schedules ) {
		foreach ( $schedules as $schedule ) {
			$this->schedules[ $schedule->name() ] = $schedule;
		}
	}

	/**
	 * Get all schedules in the collection.
	 *
	 * @return array<string, Schedule>
	 */
	public function all(): array {
		return $this->schedules;
	}

	/**
	 * Get the schedules formatted for the cron_schedules filter.
	 *
	 * @return array<array<string, array{interval: int, display: string}>>
	 */
	public function to_array(): array {
		return array_reduce(
			$this->all(),
			function ( array $carry, Schedule $schedule ) {
				return array_merge( $carry, $schedule->get() );
			},
			[]
		);
	}

	/**
	 * Retrieve a specific schedule by name.
	 *
	 * @param string $name The schedule name.
	 *
	 * @return Schedule|null
	 */
	public function get( string $name ): ?Schedule {
		return $this->schedules[ $name ] ?? null;
	}
}
