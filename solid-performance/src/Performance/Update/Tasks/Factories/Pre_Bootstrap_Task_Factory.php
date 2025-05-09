<?php
/**
 * @see \SolidWP\Performance\Update\Provider::register_pre_bootstrap_updater_tasks()
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Factories;

use SolidWP\Performance\Update\Tasks\Contracts\Abstract_Task_Factory;

/**
 * A collection of tasks that run early, so not all WordPress functions are available.
 *
 * Be extremely careful which tasks get placed here.
 *
 * @package SolidWP\Performance
 */
final class Pre_Bootstrap_Task_Factory extends Abstract_Task_Factory {

}
