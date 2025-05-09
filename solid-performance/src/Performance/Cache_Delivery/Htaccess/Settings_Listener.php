<?php
/**
 * Listen for settings changes and toggle the htaccess rules.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Page_Cache\Purge\Batch\Batch_Purger;
use SolidWP\Performance\StellarWP\Arrays\Arr;

/**
 * Listen for settings changes and toggle the htaccess rules.
 *
 * @package SolidWP\Performance
 */
final class Settings_Listener {

	/**
	 * @var Manager
	 */
	private Manager $manager;

	/**
	 * @var Batch_Purger
	 */
	private Batch_Purger $purger;

	/**
	 * @param Manager      $manager The htaccess manager.
	 * @param Batch_Purger $batch_purger The batch purger.
	 */
	public function __construct( Manager $manager, Batch_Purger $batch_purger ) {
		$this->manager = $manager;
		$this->purger  = $batch_purger;
	}

	/**
	 * Toggle adding/removing htaccess rules and clearing the cache if exclusions changed
	 * based on the settings change.
	 *
	 * @action solidwp/performance/settings/changed
	 *
	 * @param mixed[] $old_settings The old settings array.
	 * @param mixed[] $new_settings The new settings array.
	 *
	 * @return void
	 */
	public function on_settings_change( array $old_settings, array $new_settings ): void {
		$old_delivery_method = Arr::get( $old_settings, 'page_cache.cache_delivery.method' );
		$new_delivery_method = Arr::get( $new_settings, 'page_cache.cache_delivery.method' );

		// If exclusions changed, flush the entire cache.
		if ( $new_delivery_method === Cache_Delivery_Type::HTACCESS ) {
			$old_exclusions = Arr::get( $old_settings, 'page_cache.exclusions' );
			$new_exclusions = Arr::get( $new_settings, 'page_cache.exclusions' );

			if ( $old_exclusions !== $new_exclusions && ! empty( $new_exclusions ) ) {
				$this->purger->queue_purge_all();
			}
		}

		// Add or remove htaccess rules based on the cache delivery method change.
		if ( $old_delivery_method !== $new_delivery_method ) {
			if ( $new_delivery_method === Cache_Delivery_Type::HTACCESS ) {
				$this->manager->add_rules( true );
			} else {
				$this->manager->remove_rules();
			}
		}
	}
}
