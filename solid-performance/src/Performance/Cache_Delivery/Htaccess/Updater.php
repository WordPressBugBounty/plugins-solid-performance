<?php
/**
 * The htaccess rule and config updater.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use InvalidArgumentException;
use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Config\Config;

/**
 * The htaccess rule and config updater.
 *
 * @package SolidWP\Performance
 */
final class Updater {

	/**
	 * @var Manager
	 */
	private Manager $manager;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param Manager $manager The htaccess manager.
	 * @param Config  $config The config object.
	 */
	public function __construct( Manager $manager, Config $config ) {
		$this->manager = $manager;
		$this->config  = $config;
	}

	/**
	 * Update the htaccess rules and config based on apache support.
	 *
	 * @throws InvalidArgumentException If an unsupported cache delivery method is provided.
	 *
	 * @return void
	 */
	public function update_rules(): void {
		/**
		 * Filter whether the updater considers htaccess supported.
		 *
		 * @internal
		 *
		 * @param bool $supported
		 */
		$supported = (bool) apply_filters( 'solidwp/performance/htaccess/updater/supported', $this->manager->supported() );

		// If supported and enabled, add our rules.
		if ( $supported && $this->manager->enabled() ) {
			$this->manager->add_rules( true );

			return;
		}

		// If unsupported but enabled (the default), remove our rules and set the cache delivery method to PHP.
		if ( $this->manager->enabled() ) {
			$this->manager->remove_rules();

			$this->config->set(
				'page_cache.cache_delivery.method',
				Cache_Delivery_Type::PHP
			)->save();
		}
	}
}
