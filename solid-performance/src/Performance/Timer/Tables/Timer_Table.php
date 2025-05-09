<?php
/**
 * The timer table to store timers.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer\Tables;

use SolidWP\Performance\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use SolidWP\Performance\StellarWP\Schema\Tables\Contracts\Table;

/**
 * The timer table to store timers.
 *
 * @package SolidWP\Performance
 */
final class Timer_Table extends Table {

	const SCHEMA_VERSION = '1.0.3';

	/**
	 * @var string The base table name.
	 */
	protected static $base_table_name = 'swp_timers';

	/**
	 * @var string The organizational group this table belongs to.
	 */
	protected static $group = 'swpsp';

	/**
	 * @var string|null The slug used to identify the custom table.
	 */
	protected static $schema_slug = 'timers';

	/**
	 * @var string The field that uniquely identifies a row in the table.
	 */
	protected static $uid_column = 'timer_name';

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
		    `timer_name` varchar(191) NOT NULL,
		    `start_time` double NOT NULL,
		    PRIMARY KEY (`timer_name`)
		) {$charset_collate};
		";
	}
}
