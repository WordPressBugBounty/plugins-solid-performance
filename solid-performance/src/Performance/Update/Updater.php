<?php
/**
 * Runs pre/post bootstrap tasks when the plugin is updated.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update;

use Plugin_Upgrader;
use SolidWP\Performance\Flintstone\Flintstone;
use SolidWP\Performance\Shutdown\Contracts\Terminable;
use SolidWP\Performance\Update\Tasks\Factories\Non_Essential_Task_Factory;
use SolidWP\Performance\Update\Tasks\Factories\Post_Bootstrap_Task_Factory;
use SolidWP\Performance\Update\Tasks\Factories\Pre_Bootstrap_Task_Factory;
use WP_Upgrader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Runs pre/post bootstrap tasks when the plugin is updated.
 *
 * @package SolidWP\Performance
 */
final class Updater implements Terminable {

	/**
	 * The flat file key/value store db.
	 *
	 * @var Flintstone
	 */
	private Flintstone $db;

	/**
	 * The full server path to the main plugin file.
	 *
	 * @var string
	 */
	private string $plugin_file;

	/**
	 * Tasks that may run before WordPress is bootstrapped.
	 *
	 * @var Pre_Bootstrap_Task_Factory
	 */
	private Pre_Bootstrap_Task_Factory $pre_task_factory;

	/**
	 * Task that may run after WordPress is fully bootstrapped.
	 *
	 * @var Post_Bootstrap_Task_Factory
	 */
	private Post_Bootstrap_Task_Factory $post_task_factory;

	/**
	 * Tasks that may run on the 'upgrader_process_complete' hook.
	 *
	 * @var Non_Essential_Task_Factory
	 */
	private Non_Essential_Task_Factory $non_essential_task_factory;

	/**
	 * Whether we should flush the key/value store db.
	 *
	 * @var bool
	 */
	private bool $should_flush = false;

	/**
	 * @param Flintstone                  $db The flat file key/value store db.
	 * @param string                      $plugin_file The full server path to the main plugin file.
	 * @param Pre_Bootstrap_Task_Factory  $pre_task_factory Tasks that may run before WordPress is bootstrapped.
	 * @param Post_Bootstrap_Task_Factory $post_task_factory Tasks that may run after WordPress is fully bootstrapped.
	 * @param Non_Essential_Task_Factory  $non_essential_task_factory Tasks the run on the upgrader_process_complete hook.
	 */
	public function __construct(
		Flintstone $db,
		string $plugin_file,
		Pre_Bootstrap_Task_Factory $pre_task_factory,
		Post_Bootstrap_Task_Factory $post_task_factory,
		Non_Essential_Task_Factory $non_essential_task_factory
	) {
		$this->db                         = $db;
		$this->plugin_file                = $plugin_file;
		$this->pre_task_factory           = $pre_task_factory;
		$this->post_task_factory          = $post_task_factory;
		$this->non_essential_task_factory = $non_essential_task_factory;
	}

	/**
	 * Attempt to execute any pre bootstrap tasks.
	 *
	 * WARNING: This runs BEFORE WordPress is fully bootstrapped, so not all WP functions are available.
	 *
	 * @return void
	 */
	public function run_pre_bootstrap_tasks(): void {
		$tasks = $this->pre_task_factory->make();

		foreach ( $tasks as $task ) {
			if ( $task->should_run() ) {
				$task->run();
			}
		}
	}

	/**
	 * Attempt to execute any post bootstrap tasks.
	 *
	 * @action plugins_loaded
	 *
	 * @return void
	 */
	public function run_post_bootstrap_tasks(): void {
		// Don't process these tasks if our plugin isn't active.
		if ( ! did_action( 'solidwp/performance/bootstrap_file_loaded' ) ) {
			return;
		}

		$tasks = $this->post_task_factory->make();

		foreach ( $tasks as $task ) {
			if ( $task->should_run() ) {
				$this->should_flush = true;
				$task->run();
			}
		}
	}

	/**
	 * Run non-essential updates when the upgrade process is complete.
	 *
	 * @action upgrader_process_complete
	 *
	 * @param WP_Upgrader $upgrader The WordPress upgrader class performing the upgrade.
	 * @param array       $hook_extra Extra info about the upgrade process.
	 *
	 * @return void
	 */
	public function run_non_essential_tasks( WP_Upgrader $upgrader, array $hook_extra ): void {
		if ( ! ( $upgrader instanceof Plugin_Upgrader ) || 'plugin' !== ( $hook_extra['type'] ?? null ) ) {
			return;
		}

		$plugins = $hook_extra['plugins'] ?? [];

		// Check if it's our plugin being updated.
		if ( ! in_array( plugin_basename( $this->plugin_file ), $plugins, true ) ) {
			return;
		}

		// Don't process these tasks if our plugin isn't active.
		if ( ! did_action( 'solidwp/performance/bootstrap_file_loaded' ) ) {
			return;
		}

		$tasks = $this->non_essential_task_factory->make();

		foreach ( $tasks as $task ) {
			if ( $task->should_run() ) {
				$task->run();
			}
		}
	}

	/**
	 * Clear the key/value store if any of the post bootstrap tasks ran.
	 *
	 * @action shutdown
	 * @action solidwp/performance/terminate
	 *
	 * @return void
	 */
	public function terminate(): void {
		if ( ! $this->should_flush ) {
			return;
		}

		// Terminable shutdown runs twice, we only need this once.
		$this->should_flush = false;

		$this->db->flush();
	}
}
