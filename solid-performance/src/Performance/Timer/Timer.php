<?php
/**
 * Calculate the duration of an event across threads.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer;

use SolidWP\Performance\StellarWP\DB\DB;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\QueryBuilder;
use SolidWP\Performance\Timer\Exceptions\InvalidTimerNameException;
use SolidWP\Performance\Timer\Exceptions\NoActiveTimerException;
use SolidWP\Performance\Timer\Tables\Timer_Table;

/**
 * Calculate the duration of an event across threads.
 *
 * @package SolidWP\Performance
 */
final class Timer {

	/**
	 * The un-prefixed table for storing timers.
	 *
	 * @var string
	 */
	private string $table;

	/**
	 * @var Duration_Factory
	 */
	private Duration_Factory $factory;

	/**
	 * @param Duration_Factory $factory The duration factory.
	 */
	public function __construct( Duration_Factory $factory ) {
		$this->table   = Timer_Table::table_name( false );
		$this->factory = $factory;
	}

	/**
	 * Start a timer.
	 *
	 * @param  string $name The unique name for the timer.
	 *
	 * @throws InvalidTimerNameException If the timer name is invalid.
	 *
	 * @return float The start time in nanoseconds.
	 */
	public function start( string $name ): float {
		if ( ! strlen( $name ) ) {
			throw new InvalidTimerNameException( 'The timer name cannot be empty.' );
		}

		$start = microtime( true );

		$this->query()->upsert(
			[
				'timer_name' => $name,
				'start_time' => $start,
			],
			[
				'timer_name',
			],
			[
				'%s',
				'%f',
			]
		);

		return $start;
	}

	/**
	 * Stop the timer and return the Duration object.
	 *
	 * @param  string $name The unique name for the timer.
	 *
	 * @throws InvalidTimerNameException If the timer name is invalid.
	 * @throws NoActiveTimerException If the timer is not found.
	 *
	 * @return Duration
	 */
	public function stop( string $name ): Duration {
		if ( ! strlen( $name ) ) {
			throw new InvalidTimerNameException( 'The timer name cannot be empty.' );
		}

		$timer = $this->query()->where( 'timer_name', $name )->get();

		if ( ! $timer ) {
			throw new NoActiveTimerException( sprintf( 'No timer named "%s" was found.', $name ) );
		}

		$seconds = microtime( true ) - $timer->start_time;

		$this->clear( $name );

		return $this->factory->make( $seconds );
	}

	/**
	 * Clear a timer.
	 *
	 * @param  string $name The unique name for the timer.
	 *
	 * @return bool
	 */
	public function clear( string $name ): bool {
		return (bool) $this->query()
							->where( 'timer_name', $name )
							->delete();
	}

	/**
	 * Clear all timers.
	 *
	 * @return bool
	 */
	public function clear_all(): bool {
		global $wpdb;

		$table = $wpdb->prefix . $this->table;

		$query = DB::raw( "TRUNCATE TABLE $table" );

		return (bool) DB::query( $query->sql );
	}

	/**
	 * Get the query builder for the timers table.
	 *
	 * @return QueryBuilder
	 */
	private function query(): QueryBuilder {
		return DB::table( $this->table );
	}
}
