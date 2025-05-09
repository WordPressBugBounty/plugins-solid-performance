<?php
/**
 * Attempts to update our htaccess rules if the advanced cache pre bootstrap
 * task is also being performed.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Post_Bootstrap;

use InvalidArgumentException;
use SolidWP\Performance\Cache_Delivery\Htaccess\Updater;
use SolidWP\Performance\Flintstone\Flintstone;
use SolidWP\Performance\Update\Tasks\Contracts\Task;
use SolidWP\Performance\Update\Tasks\Pre_Bootstrap\Advanced_Cache_Remover;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Attempts to update our htaccess rules if the advanced cache pre bootstrap
 * task is also being performed.
 *
 * @package SolidWP\Performance
 */
final class Update_Htaccess_Rules implements Task {

	/**
	 * The flat file key/value store db.
	 *
	 * @var Flintstone
	 */
	private Flintstone $db;

	/**
	 * @var Updater
	 */
	private Updater $updater;

	/**
	 * @param Flintstone $db The flat file key/value store db.
	 * @param Updater    $updater The htaccess updater.
	 */
	public function __construct( Flintstone $db, Updater $updater ) {
		$this->db      = $db;
		$this->updater = $updater;
	}

	/**
	 * If the advanced cache file is being updated, let's update our .htaccess rules
	 * as well.
	 *
	 * @see Advanced_Cache_Remover::should_run()
	 *
	 * @return bool
	 */
	public function should_run(): bool {
		return (bool) $this->db->get( Advanced_Cache_Remover::KEY );
	}

	/**
	 * Add or remove our htaccess rules based on server support.
	 *
	 * @throws InvalidArgumentException If an invalid cache delivery method is provided.
	 *
	 * @return void
	 */
	public function run(): void {
		$this->updater->update_rules();
	}
}
