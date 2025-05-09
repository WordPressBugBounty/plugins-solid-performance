<?php
/**
 * The database lock driver. Stores locks in a custom table.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock\Drivers;

use SolidWP\Performance\Lock\Contracts\Lock;
use SolidWP\Performance\Lock\Lock_Entry;
use SolidWP\Performance\StellarWP\DB\DB;
use SolidWP\Performance\StellarWP\DB\QueryBuilder\QueryBuilder;
use Throwable;

/**
 * The database lock driver.
 *
 * @package SolidWP\Performance
 */
final class Database_Driver implements Lock {

	/**
	 * @var Lock_Entry
	 */
	private Lock_Entry $lock_entry;

	/**
	 * The un-prefixed table name for storing locks.
	 *
	 * @var string
	 */
	private string $table;

	/**
	 * The prune probability odds.
	 *
	 * @var array{0: int, 1: int}
	 */
	private array $lottery;

	/**
	 * @param Lock_Entry            $lock_entry The lock entry.
	 * @param string                $table The un-prefixed database table name where locks are stored.
	 * @param array{0: int, 1: int} $lottery The prune probability odds.
	 */
	public function __construct(
		Lock_Entry $lock_entry,
		string $table,
		array $lottery = [ 2, 100 ]
	) {
		$this->lock_entry = $lock_entry;
		$this->table      = $table;
		$this->lottery    = $lottery;
	}

	/**
	 * Attempt to acquire a lock.
	 *
	 * @return bool
	 */
	public function acquire(): bool {
		global $wpdb;

		// Don't log duplicate key errors to the error log.
		$suppress = $wpdb->suppress_errors();

		try {
			$this->query()->insert(
				[
					'lock_name'  => $this->lock_entry->name(),
					'lock_owner' => $this->lock_entry->owner(),
					'expiration' => $this->expires_at(),
				]
			);

			$acquired = true;
		} catch ( Throwable $e ) {
			$updated = $this->query()->where( 'lock_name', $this->lock_entry->name() )
							->where( 'expiration', time(), '<=' )
							->update(
								[
									'lock_owner' => $this->lock_entry->owner(),
									'expiration' => $this->expires_at(),
								]
							);

			$acquired = $updated >= 1;
		}

		// Resume original error suppression.
		$wpdb->suppress_errors( $suppress );

		// Lottery to randomly prune expired locks. Move to a WP cron job one day.
		if ( random_int( 1, $this->lottery[1] ) <= $this->lottery[0] ) {
			$table = $wpdb->prefix . $this->table;

			$query = DB::raw(
				"
				DELETE FROM $table
				WHERE `expiration` <= %d
			",
				[
					time(),
				]
			);

			DB::query( $query->sql );
		}

		return $acquired;
	}

	/**
	 * Determine if this lock is already acquired.
	 *
	 * @return bool
	 */
	public function is_acquired(): bool {
		// Grab the CONSTANT 1, should be most efficient.
		$sql = $this->query()->select( '1' )
					->where( 'lock_name', $this->lock_entry->name() )
					->where( 'expiration', time(), '>' )
					->limit( 1 )
					->getSQL();

		return (bool) DB::get_var( $sql );
	}

	/**
	 * Get the original owner of the lock.
	 *
	 * @return string
	 */
	public function owner(): string {
		return (string) DB::get_var(
			$this->query()->select( 'lock_owner' )
							->where( 'lock_name', $this->lock_entry->name() )
							->getSQL()
		);
	}

	/**
	 * Release the lock, if the original owner matches the current one.
	 *
	 * @return bool
	 */
	public function release(): bool {
		$owner = $this->owner();

		if ( ! strlen( $owner ) ) {
			return false;
		}

		if ( $owner !== $this->lock_entry->owner() ) {
			return false;
		}

		return $this->force_release();
	}

	/**
	 * Force release the lock, regardless of who owns it.
	 *
	 * @return bool
	 */
	public function force_release(): bool {
		return (bool) $this->query()
							->where( 'lock_name', $this->lock_entry->name() )
							->delete();
	}

	/**
	 * Determine the expiration time based on now + the lock's expiration in seconds.
	 *
	 * @return int
	 */
	private function expires_at(): int {
		$seconds = $this->lock_entry->expiration() > 0
			? $this->lock_entry->expiration()
			: YEAR_IN_SECONDS * 10; // 10 years in seconds.

		return time() + $seconds;
	}

	/**
	 * Get the query builder for this table.
	 *
	 * @return QueryBuilder
	 */
	private function query(): QueryBuilder {
		return DB::table( $this->table );
	}
}
