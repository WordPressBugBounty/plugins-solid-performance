<?php
/**
 * Registers database related functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Database;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Lock\Tables\Cache_Lock;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use SolidWP\Performance\StellarWP\DB\DB;
use SolidWP\Performance\StellarWP\Schema\Config;
use SolidWP\Performance\StellarWP\Schema\Register;
use SolidWP\Performance\Timer\Tables\Timer_Table;

/**
 * Registers database related functionality in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const SCHEMA_TABLES = 'solid_performance.database.schema_tables';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_schema();
	}

	/**
	 * Configure the stellarwp/schema library.
	 *
	 * @throws DatabaseQueryException If we failed to create or update the database tables.
	 *
	 * @return void
	 */
	private function register_schema(): void {
		Config::set_container( $this->container );
		Config::set_db( DB::class );

		// Add all schema tables to be registered here.
		$this->container->setVar(
			self::SCHEMA_TABLES,
			[
				Cache_Lock::class,
				Timer_Table::class,
			]
		);

		try {
			Register::tables( $this->container->getVar( self::SCHEMA_TABLES ) );
		} catch ( DatabaseQueryException $e ) {
			$this->container->get( LoggerInterface::class )->emergency(
				'Unable to create or update database tables',
				[
					'query_errors' => $e->getQueryErrors(),
					'query'        => $e->getQuery(),
					'exception'    => $e,
				]
			);

			throw $e;
		}
	}
}
