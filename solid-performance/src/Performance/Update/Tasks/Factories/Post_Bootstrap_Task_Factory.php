<?php
/**
 * @see \SolidWP\Performance\Update\Provider::register_post_bootstrap_updater_tasks()
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Update\Tasks\Factories;

use SolidWP\Performance\Update\Tasks\Contracts\Abstract_Task_Factory;

/**
 * A collection of tasks that run once WordPress is fully bootstrapped.
 *
 * @package SolidWP\Performance
 */
final class Post_Bootstrap_Task_Factory extends Abstract_Task_Factory {

}
