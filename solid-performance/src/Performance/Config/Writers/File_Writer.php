<?php
/**
 * Writes only config items that differ from the defaults to a config
 * file on disk.
 *
 * This attempts to sync the database config items to the file based config.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Config\Writers;

use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Config\Config_File;
use SolidWP\Performance\Config\Default_Config;
use SolidWP\Performance\Config\Writers\Contracts\Writable;

/**
 * Writes only config items that differ from the defaults to a config
 * file on disk.
 *
 * This attempts to sync the database config items to the file based config.
 *
 * @package SolidWP\Performance
 */
final class File_Writer implements Writable {

	/**
	 * The config singleton.
	 *
	 * @var Config
	 */
	private Config $config;

	/**
	 * The config file location.
	 *
	 * @var Config_File
	 */
	private Config_File $config_file;

	/**
	 * The default configuration items.
	 *
	 * @var Default_Config
	 */
	private Default_Config $default_config;

	/**
	 * @param  Config         $config The config singleton.
	 * @param  Config_File    $config_file The config file location.
	 * @param  Default_Config $default_config The default configuration items.
	 */
	public function __construct( Config $config, Config_File $config_file, Default_Config $default_config ) {
		$this->config         = $config;
		$this->config_file    = $config_file;
		$this->default_config = $default_config;
	}

	/**
	 * Write config items that differ from the default values to a config file on disk.
	 *
	 * Fires automatically after our settings are saved.
	 *
	 * @action solidwp/performance/config/write_file
	 *
	 * @action add_option_solid_performance_settings
	 * @action update_option_solid_performance_settings
	 *
	 * @action add_site_option_solid_performance_settings
	 * @action update_site_option_solid_performance_settings
	 *
	 * @return bool
	 */
	public function save(): bool {
		// Make sure that the config directory exists.
		if ( ! is_dir( $this->config_file->dir() ) ) {
			wp_mkdir_p( $this->config_file->dir() );
		}

		// Refresh the config and only store items that differ from the default configuration.
		$items = array_diff_multidimensional( $this->config->refresh()->all(), $this->default_config->get(), false );

		// Get the config as a string to write to file.
		$config = var_export( $items, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export

		// Manually create the return for the file.
		$contents = sprintf(
			'<?php
/**
* Automatically Generated by Solid Performance every time the settings page is saved.
*/
return %s;',
			$config
		);

		return (bool) file_put_contents( $this->config_file->get(), $contents );
	}
}
