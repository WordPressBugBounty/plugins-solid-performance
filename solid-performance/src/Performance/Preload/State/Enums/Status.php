<?php
/**
 * The preloader status pseudo-enum.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\State\Enums;

/**
 * The preloader status pseudo-enum.
 *
 * @package SolidWP\Performance
 */
final class Status {

	public const IDLE      = 'idle';
	public const RUNNING   = 'running';
	public const CANCELED  = 'canceled';
	public const COMPLETED = 'completed';
	public const FAILED    = 'failed';

	/**
	 * Prevent instantiation of pseudo-enum.
	 */
	private function __construct() {
	}

	/**
	 * Validate if the provided status is valid.
	 *
	 * @param  string $status The status type.
	 *
	 * @return bool
	 */
	public static function is_valid( string $status ): bool {
		return in_array(
			$status,
			[
				self::IDLE,
				self::RUNNING,
				self::CANCELED,
				self::COMPLETED,
				self::FAILED,
			],
			true
		);
	}
}
