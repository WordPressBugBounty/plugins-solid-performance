<?php
/**
 * The preload monitor repository to store the status.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Monitor;

/**
 * The preload monitor repository to store the status.
 *
 * @package SolidWP\Performance
 */
final class Repository {

	public const OPTION = 'swpsp_preload_monitor';

	/**
	 * Get preloader monitor status.
	 *
	 * @return array{count: int, last_activity: int, retries: int}
	 */
	public function get(): array {
		return (array) get_option( self::OPTION, [] );
	}

	/**
	 * Set the current URL count, current time and retries to compare to later.
	 *
	 * @param int $url_count The current URL count.
	 * @param int $retries The incremented retries.
	 *
	 * @return bool
	 */
	public function set( int $url_count, int $retries = 0 ): bool {
		$status = [
			'count'         => $url_count,
			'last_activity' => time(),
			'retries'       => $retries,
		];

		return update_option( self::OPTION, $status, false );
	}

	/**
	 * Delete the monitor data.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return delete_option( self::OPTION );
	}
}
