<?php
/**
 * The cache lock table schema to store atomic locks.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock\Tables;

use SolidWP\Performance\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use SolidWP\Performance\StellarWP\Schema\Tables\Contracts\Table;

/**
 * The cache lock table schema to store atomic locks.
 *
 * @package SolidWP\Performance
 */
final class Cache_Lock extends Table {

	const SCHEMA_VERSION = '1.0.2';

	/**
	 * @var string The base table name.
	 */
	protected static $base_table_name = 'swp_cache_lock';

	/**
	 * @var string The organizational group this table belongs to.
	 */
	protected static $group = 'swpsp';

	/**
	 * @var string|null The slug used to identify the custom table.
	 */
	protected static $schema_slug = 'cache-lock';

	/**
	 * @var string The field that uniquely identifies a row in the table.
	 */
	protected static $uid_column = 'lock_name';

	/**
	 * Overload the update method to first drop the database as this is a temporary table.
	 *
	 * @throws DatabaseQueryException If any of the queries fail.
	 *
	 * @return string[]
	 */
	public function update() {
		if ( $this->exists() ) {
			$this->drop();
		}

		return parent::update();
	}

	/**
	 * @return string
	 */
	protected function get_definition(): string {
		global $wpdb;
		$table_name      = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		return "
			CREATE TABLE `$table_name` (
		    `lock_name` varchar(191) NOT NULL,
		    `lock_owner` varchar(191) NOT NULL,
		    `expiration` bigint NOT NULL,
		    PRIMARY KEY (`lock_name`)
		) {$charset_collate};
		";
	}
}
