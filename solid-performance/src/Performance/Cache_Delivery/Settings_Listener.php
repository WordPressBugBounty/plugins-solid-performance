<?php
/**
 * Listen for settings changes and run different events when the cache delivery method changes.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery;

use SolidWP\Performance\Page_Cache\Purge\Batch\Batch_Purger;
use SolidWP\Performance\StellarWP\Arrays\Arr;

/**
 * Listen for settings changes and run different events when the cache delivery method changes.
 *
 * @package SolidWP\Performance
 */
final class Settings_Listener {

	/**
	 * @var Manager_Collection
	 */
	private Manager_Collection $manager_collection;

	/**
	 * @var Batch_Purger
	 */
	private Batch_Purger $purger;

	/**
	 * @param Manager_Collection $manager_collection The cache delivery collection.
	 * @param Batch_Purger       $batch_purger The batch purger.
	 */
	public function __construct(
		Manager_Collection $manager_collection,
		Batch_Purger $batch_purger
	) {
		$this->manager_collection = $manager_collection;
		$this->purger             = $batch_purger;
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
		if ( $new_delivery_method === Cache_Delivery_Type::HTACCESS || $new_delivery_method === Cache_Delivery_Type::NGINX ) {
			$old_exclusions = Arr::get( $old_settings, 'page_cache.exclusions' );
			$new_exclusions = Arr::get( $new_settings, 'page_cache.exclusions' );

			if ( $old_exclusions !== $new_exclusions && ! empty( $new_exclusions ) ) {
				$this->purger->queue_purge_all();
			}
		}

		if ( $old_delivery_method !== $new_delivery_method ) {
			$htaccess_manager = $this->manager_collection->get( Cache_Delivery_Type::HTACCESS );

			// Add or remove htaccess rules based on the cache delivery method change.
			if ( $new_delivery_method === Cache_Delivery_Type::HTACCESS ) {
				$htaccess_manager->add_rules( true );
			} elseif ( $old_delivery_method === Cache_Delivery_Type::HTACCESS ) {
				$htaccess_manager->remove_rules();
			}

			$nginx_manager = $this->manager_collection->get( Cache_Delivery_Type::NGINX );

			// Update our nginx.conf rules, or add cache bypass rules in order to ensure the file exists.
			if ( $new_delivery_method === Cache_Delivery_Type::NGINX ) {
				$nginx_manager->add();
			} elseif ( $old_delivery_method === Cache_Delivery_Type::NGINX ) {
				$nginx_manager->bypass();
			}
		}
	}
}
