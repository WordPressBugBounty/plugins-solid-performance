<?php
/**
 * @see \SolidWP\Performance\Update\Provider::register_non_essential_updater_tasks()
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Factories;

use SolidWP\Performance\Update\Tasks\Contracts\Abstract_Task_Factory;

/**
 * A collection of tasks that run on 'upgrader_process_complete' hook. This isn't
 * always guaranteed to fire, so any tasks here aren't necessary for the plugin to
 * function, but it would be nice if they ran.
 *
 * @package SolidWP\Performance
 */
final class Non_Essential_Task_Factory extends Abstract_Task_Factory {

}
