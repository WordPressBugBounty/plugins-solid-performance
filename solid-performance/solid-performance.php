<?php
/**
 * Plugin Name: Solid Performance
 * Description: Optimize site performance, boost PageSpeed, and serve a faster website with this simple site optimization tool from SolidWP. Easy page caching setup will accelerate your site in minutes with only a couple of clicks.
 * Author: SolidWP
 * Author URI: https://go.solidwp.com/performance-author
 * Version: 1.7.1
 * Text Domain: solid-performance
 * Domain Path: /lang
 * License: GPLv2-or-later
 * Requires at least: 6.4
 * Requires PHP: 7.4
 *
 * @package SolidWP\Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SolidWP\Performance\Plugin\Activator;
use SolidWP\Performance\Plugin\Deactivator;
use SolidWP\Performance\Plugin\Uninstaller;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/vendor-prefixed/autoload.php';
require_once __DIR__ . '/src/functions/app.php';
require_once __DIR__ . '/src/functions/filesystem.php';
require_once __DIR__ . '/src/functions/config.php';

define( 'SWPSP_PLUGIN_FILE', __FILE__ );

// Get the plugin's singleton instance and fully initialize the container definitions.
$core = swpsp_plugin()->init();

/**
 * Fires when the SolidWP Performance plugin is loaded.
 *
 * @since TBD
 */
do_action( 'solidwp/performance/bootstrap_file_loaded' );

register_activation_hook( __FILE__, new Activator( $core->container() ) );
register_deactivation_hook( __FILE__, new Deactivator( $core->container() ) );
register_uninstall_hook( __FILE__, [ Uninstaller::class, 'uninstall' ] );
