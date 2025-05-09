<?php
/**
 * The Service Provider to register updater tasks in the container.
 *
 * This provider is booted early, when advanced-cache.php is.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RuntimeException;
use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Core;
use SolidWP\Performance\Flintstone\Flintstone;
use SolidWP\Performance\Update\Tasks\Factories\Non_Essential_Task_Factory;
use SolidWP\Performance\Update\Tasks\Factories\Post_Bootstrap_Task_Factory;
use SolidWP\Performance\Update\Tasks\Factories\Pre_Bootstrap_Task_Factory;
use SolidWP\Performance\Update\Tasks\Post_Bootstrap\Enable_Scheduled_Tasks;
use SolidWP\Performance\Update\Tasks\Post_Bootstrap\Advanced_Cache_Restorer;
use SolidWP\Performance\Update\Tasks\Once\Clean_Up_Old_Cache_Dir;
use SolidWP\Performance\Update\Tasks\Post_Bootstrap\Update_Htaccess_Rules;
use SolidWP\Performance\Update\Tasks\Pre_Bootstrap\Advanced_Cache_Remover;

/**
 * The Service Provider to register updater tasks in the container.
 *
 * This provider is booted early, when advanced-cache.php is.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const DB = 'solid_performance.update_db';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_key_value_store();
		$this->register_pre_bootstrap_updater_tasks();
		$this->register_post_bootstrap_updater_tasks();
		$this->register_non_essential_updater_tasks();
		$this->register_updater();
	}

	/**
	 * Flintstone is a simple file based key/value store that tasks can use
	 * to read and write to, so that a pre bootstrap task can signal to a post
	 * bootstrap task that it should perform some action.
	 *
	 * DO NOT store sensitive information in this store.
	 *
	 * @throws RuntimeException If we can't find a writable directory.
	 *
	 * @return void
	 */
	private function register_key_value_store(): void {
		$this->container->singleton(
			Flintstone::class,
			static function (): Flintstone {
				// DB path is: wp-content/solid_performance_updates.dat.
				// List of directories to try to write to, in order.
				return new Flintstone(
					'solid_performance_updates',
					[
						'dir' => rtrim( WP_CONTENT_DIR, '/\\' ) . '/',
					]
				);
			}
		);
	}

	/**
	 * Register tasks that run before WordPress is fully bootstrapped.
	 *
	 * Use caution, as not all WordPress functions are available and the database isn't initialized
	 * yet.
	 *
	 * @return void
	 */
	private function register_pre_bootstrap_updater_tasks(): void {
		$this->container->when( Advanced_Cache_Remover::class )
						->needs( '$version' )
						->give( static fn( $c ): string => $c->get( Core::PLUGIN_VERSION ) );

		$this->container->when( Advanced_Cache_Remover::class )
						->needs( '$destination' )
						->give( static fn( $c ): string => $c->get( Core::ADVANCED_CACHE_PATH ) );

		$this->container->when( Pre_Bootstrap_Task_Factory::class )
						->needs( '$tasks' )
						->give(
							static fn(): array => [
								// Add tasks that will run before WordPress is fully bootstrapped.
								// WARNING: Be careful with which WP functions you use, they may not be available.
								Advanced_Cache_Remover::class,
							]
						);
	}

	/**
	 * Register tasks that run once WordPress is fully bootstrapped.
	 *
	 * @return void
	 */
	private function register_post_bootstrap_updater_tasks(): void {
		$this->container->when( Post_Bootstrap_Task_Factory::class )
						->needs( '$tasks' )
						->give(
							static fn(): array => [
								// Add tasks that will run after WordPress is fully bootstrapped.
								Advanced_Cache_Restorer::class,
								Update_Htaccess_Rules::class,
								Enable_Scheduled_Tasks::class,
							]
						);
	}

	/**
	 * Register tasks that aren't required for the plugin to function.
	 *
	 * These run on the run on the `upgrader_process_complete` hook.
	 *
	 * @return void
	 */
	private function register_non_essential_updater_tasks(): void {
		$this->container->when( Non_Essential_Task_Factory::class )
						->needs( '$tasks' )
						->give(
							static fn(): array => [
								// Add tasks that will run on the `upgrader_process_complete` hook.
								Clean_Up_Old_Cache_Dir::class,
							]
						);
	}

	/**
	 * Register the updater and the tasks that will run be pre/post boostrap.
	 *
	 * @return void
	 */
	private function register_updater(): void {
		$this->container->when( Updater::class )
						->needs( '$plugin_file' )
						->give( static fn( $c ) => $c->get( Core::PLUGIN_FILE ) );

		// Ensure singleton for terminable shutdown task.
		$this->container->singleton( Updater::class, Updater::class );

		/*
		 * Attempt to run any pre bootstrap tasks BEFORE WordPress is fully bootstrapped.
		 *
		 * WARNING: This runs BEFORE WordPress is fully bootstrapped, be careful
		 * with the tasks you assign above, they must not rely on any WordPress functionality
		 * that doesn't exist when advanced-cache.php is loaded.
		 */
		$this->container->get( Updater::class )->run_pre_bootstrap_tasks();

		// Attempt to run any post bootstrap tasks, AFTER WordPress is fully bootstrapped.
		add_action( 'init', $this->container->callback( Updater::class, 'run_post_bootstrap_tasks' ), 1 );

		// Run non-essential tasks when WordPress fires the upgrader_process_complete action for our plugin.
		add_action(
			'upgrader_process_complete',
			$this->container->callback( Updater::class, 'run_non_essential_tasks' ),
			10,
			2
		);
	}
}
