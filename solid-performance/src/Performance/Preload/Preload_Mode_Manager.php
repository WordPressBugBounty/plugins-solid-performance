<?php
/**
 * The Preload Mode Manager, allows enabling/disabling and getting the status of
 * high performance mode.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Config\Config;

/**
 * The Preload Mode Manager, allows enabling/disabling and getting the status of
 * high performance mode.
 *
 * @package SolidWP\Performance
 */
class Preload_Mode_Manager {

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * The preload high performance mode config key.
	 *
	 * @var string
	 */
	private string $config_key;

	/**
	 * @param Config $config The config object.
	 * @param string $config_key The preload high performance mode config key.
	 */
	public function __construct( Config $config, string $config_key = 'page_cache.preload.high_performance_mode' ) {
		$this->config     = $config;
		$this->config_key = $config_key;
	}

	/**
	 * Check if high performance mode is enabled.
	 *
	 * @return bool
	 */
	public function is_high_performance_mode(): bool {
		return (bool) $this->config->refresh()->get( $this->config_key );
	}

	/**
	 * Enable high performance mode.
	 *
	 * @return void
	 */
	public function enable_high_performance_mode(): void {
		$this->config->set( $this->config_key, true )->save();
	}

	/**
	 * Disable high performance mode.
	 *
	 * @return void
	 */
	public function disable_high_performance_mode(): void {
		$this->config->set( $this->config_key, false )->save();
	}
}
