<?php
/**
 * All functionality related to uninstalling the plugin.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Plugin;

use SolidWP\Performance\Container;
use SolidWP\Performance\Database\Provider;
use SolidWP\Performance\StellarWP\Schema\Register;
use SolidWP\Performance\StellarWP\Schema\Tables\Contracts\Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles actions that should run when the plugin is uninstalled.
 *
 * WordPress does not allow anonymous callbacks with register_uninstall_hook.
 *
 * @package SolidWP\Performance
 */
final class Uninstaller {

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * The Singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * @param  Container $container The container.
	 */
	private function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( self::$instance === null ) {
			self::$instance = new self( swpsp_plugin()->container() );
		}

		return self::$instance;
	}

	/**
	 * Uninstall hook run via register_uninstall_hook().
	 *
	 * @return void
	 */
	public static function uninstall(): void {
		self::instance()->handle_uninstall();
	}

	/**
	 * Actual uninstall logic.
	 *
	 * @return void
	 */
	private function handle_uninstall(): void {
		$this->remove_database_tables();
	}

	/**
	 * Remove database tables.
	 *
	 * @return void
	 */
	private function remove_database_tables(): void {
		$this->container->get( Provider::class )->register();

		$tables = $this->container->get( Provider::SCHEMA_TABLES );

		/** @var class-string<Table> $table */
		foreach ( $tables as $table ) {
			Register::remove_table( $table );
		}
	}
}
