<?php
/**
 * Responsible for loading configuration items from different locations.
 *
 * @note    When the request begins at advanced-cache.php, the database is not yet available, and we rely on the file based
 *          configuration.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Config;

use SolidWP\Performance\Page_Cache\Cache_Path;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible for loading configuration items from different locations.
 *
 * @note    When the request begins at advanced-cache.php, the database is not yet available, and we rely on the file based
 *          configuration.
 *
 * @package SolidWP\Performance
 */
final class Config_Loader {

	public const OPTION_KEY = 'solid_performance_settings';

	/**
	 * The default config items.
	 *
	 * @var Default_Config
	 */
	private Default_Config $default_config;

	/**
	 * The config file dir/location.
	 *
	 * @var Config_File
	 */
	private Config_File $config_file;

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @var Config_Resolver
	 */
	private Config_Resolver $resolver;

	/**
	 * @var Config_Filter
	 */
	private Config_Filter $filter;

	/**
	 * @param Default_Config  $default_config The default config items.
	 * @param Config_File     $config_file The config file dir/location.
	 * @param Cache_Path      $cache_path The cache path object.
	 * @param Config_Resolver $resolver The configuration resolver.
	 * @param Config_Filter   $filter The config filter.
	 */
	public function __construct(
		Default_Config $default_config,
		Config_File $config_file,
		Cache_Path $cache_path,
		Config_Resolver $resolver,
		Config_Filter $filter
	) {
		$this->default_config = $default_config;
		$this->config_file    = $config_file;
		$this->cache_path     = $cache_path;
		$this->resolver       = $resolver;
		$this->filter         = $filter;
	}

	/**
	 * Fetches config items from multiple sources, merging them based on precedence and then
	 * filters them to ensure they only match the keys from the default config.
	 *
	 * @return array<string, mixed>
	 */
	public function load(): array {
		$merge_items = [];

		// This file is the only source available when advanced-cache.php is loaded.
		if ( file_exists( $this->config_file->get() ) ) {
			$merge_items[] = require $this->config_file->get();
		}

		// Fetch the network configuration. Note: we currently have no true MS support/settings page etc...this is just for the future.
		if ( function_exists( 'get_network_option' ) && is_multisite() ) {
			$merge_items[] = (array) get_network_option( get_main_network_id(), self::OPTION_KEY, [] );
		}

		// Allow local sites to override the network configuration.
		if ( function_exists( 'get_option' ) ) {
			$merge_items[] = (array) get_option( self::OPTION_KEY, [] );
		}

		// Filter out all keys that don't exist in the default config from the merged items.
		return $this->filter->filter( $this->merge_configs( ...$merge_items ) );
	}

	/**
	 * Merge configuration items, starting with the default configuration.
	 *
	 * @param  array<string, mixed> ...$configs The different config sources.
	 *
	 * @return array
	 */
	private function merge_configs( array ...$configs ): array {
		$merged = $this->resolver->merge_configs( $this->default_config->get(), ...$configs );

		// Force a dynamic cache_dir every time so it's always current.
		// The saved cache_dir path could now be invalid and cause all kinds of issues.
		$merged['page_cache']['cache_dir'] = $this->cache_path->get_cache_dir();

		return $merged;
	}
}
