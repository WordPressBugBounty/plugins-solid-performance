<?php
/**
 * Ensure our scheduled tasks are enabled when updating.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Post_Bootstrap;

use SolidWP\Performance\Cron\Scheduler;
use SolidWP\Performance\Flintstone\Flintstone;
use SolidWP\Performance\Update\Tasks\Contracts\Task;
use SolidWP\Performance\Update\Tasks\Pre_Bootstrap\Advanced_Cache_Remover;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure our scheduled tasks are enabled when updating.
 *
 * @package SolidWP\Performance
 */
final class Enable_Scheduled_Tasks implements Task {

	/**
	 * The flat file key/value store db.
	 *
	 * @var Flintstone
	 */
	private Flintstone $db;

	/**
	 * @var Scheduler
	 */
	private Scheduler $scheduler;

	/**
	 * @param Flintstone $db The flat file key/value store db.
	 * @param Scheduler  $scheduler The cron job task scheduler.
	 */
	public function __construct( Flintstone $db, Scheduler $scheduler ) {
		$this->db        = $db;
		$this->scheduler = $scheduler;
	}

	/**
	 * If the advanced cache file is being updated, let's run this as well.
	 *
	 * @see Advanced_Cache_Remover::should_run()
	 *
	 * @return bool
	 */
	public function should_run(): bool {
		return (bool) $this->db->get( Advanced_Cache_Remover::KEY );
	}

	/**
	 * Enable our scheduled tasks.
	 *
	 * @return void
	 */
	public function run(): void {
		$this->scheduler->enable_tasks();
	}
}
