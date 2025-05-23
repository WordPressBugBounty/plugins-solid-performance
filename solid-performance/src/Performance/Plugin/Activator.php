<?php
/**
 * All functionality related to activating the plugin.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Plugin;

use InvalidArgumentException;
use SolidWP\Performance\Cache_Delivery\Htaccess\Updater;
use SolidWP\Performance\Config\Advanced_Cache;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Config\WP_Config;
use SolidWP\Performance\Config\Writers\File_Writer;
use SolidWP\Performance\Container;
use SolidWP\Performance\Cron\Scheduler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles actions that should run when the plugin is activated.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
final class Activator {

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * @param  Container $container The container.
	 */
	private function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Lazy-instantiated callable for register_activation_hook.
	 *
	 * @return callable
	 */
	public static function callback(): callable {
		return static function (): void {
			$instance = new self( swpsp_plugin()->init()->container() );

			$instance->activate();
		};
	}

	/**
	 * Activation hook.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function activate(): void {
		$this->advanced_cache_dropin_init();
		$this->cache_directory_init();
		$this->write_settings();
		$this->wp_cache_init();
		$this->update_htaccess_rules();
		$this->register_scheduled_tasks();
	}

	/**
	 * Ensure that the advanced-cache.php drop-in is copied into place.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function advanced_cache_dropin_init(): void {
		$this->container->get( Advanced_Cache::class )->generate();
	}

	/**
	 * Ensure that the cache directory exists.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function cache_directory_init(): void {
		$cache_dir = $this->container->get( Config::class )->get( 'page_cache.cache_dir' );

		if ( ! is_dir( $cache_dir ) ) {
			wp_mkdir_p( $cache_dir );
		}
	}


	/**
	 * Set the WP_CACHE constant.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function wp_cache_init(): void {
		$this->container->get( WP_Config::class )->set_wp_cache( 'true' );
	}

	/**
	 * Saves a set of default values in the config.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function write_settings(): void {
		// Save config to the database.
		$this->container->get( Config::class )->save();

		// Force writing the config.php file again.
		$this->container->get( File_Writer::class )->save();
	}

	/**
	 * Update our htaccess rules.
	 *
	 * @throws InvalidArgumentException If an invalid cache delivery method is provided.
	 *
	 * @return void
	 */
	private function update_htaccess_rules(): void {
		$this->container->get( Updater::class )->update_rules();
	}

	/**
	 * Register scheduled tasks.
	 *
	 * @return void
	 */
	private function register_scheduled_tasks(): void {
		$this->container->get( Scheduler::class )->enable_tasks();
	}
}
