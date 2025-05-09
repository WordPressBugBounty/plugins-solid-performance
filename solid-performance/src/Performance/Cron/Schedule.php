<?php
/**
 * Represents a custom WordPress cron schedule.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron;

use InvalidArgumentException;

/**
 * Represents a custom WordPress cron schedule.
 *
 * @package SolidWP\Performance
 */
final class Schedule {

	/**
	 * The unique cron schedule name.
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * The interval in seconds.
	 *
	 * @var int
	 */
	private int $interval;

	/**
	 * The i18n friendly display name.
	 *
	 * @var string
	 */
	private string $display;

	/**
	 * @param string $name The unique cron schedule name.
	 * @param int    $interval The interval in seconds.
	 * @param string $display The i18n friendly display name.
	 *
	 * @throws InvalidArgumentException If invalid arguments are passed.
	 */
	public function __construct( string $name, int $interval, string $display ) {
		if ( $name === '' ) {
			throw new InvalidArgumentException( 'The name cannot be empty' );
		}

		if ( $interval < 1 ) {
			throw new InvalidArgumentException( 'The interval must be greater than 0' );
		}

		if ( ! strlen( $display ) ) {
			throw new InvalidArgumentException( 'The display name cannot be empty' );
		}

		$this->name     = $name;
		$this->interval = $interval;
		$this->display  = $display;
	}

	/**
	 * Return the schedule name.
	 *
	 * @return string
	 */
	public function name(): string {
		return $this->name;
	}

	/**
	 * Return the interval.
	 *
	 * @return int
	 */
	public function interval(): int {
		return $this->interval;
	}

	/**
	 * Get the schedule in the format to register via cron_schedules.
	 *
	 * @return array<string, array{interval: int, display: string}>
	 */
	public function get(): array {
		return [
			$this->name => [
				'interval' => $this->interval,
				'display'  => $this->display,
			],
		];
	}
}
