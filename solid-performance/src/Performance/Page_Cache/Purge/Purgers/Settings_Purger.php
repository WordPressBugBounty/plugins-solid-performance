<?php
/**
 * Handles Purging depending on which settings are changed.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Purge\Purgers;

use SolidWP\Performance\Page_Cache\Purge\Batch\Batch_Purger;
use SolidWP\Performance\StellarWP\Arrays\Arr;

/**
 * Handles Purging depending on which settings are changed.
 *
 * @package SolidWP\Performance
 */
final class Settings_Purger {

	/**
	 * @var Batch_Purger
	 */
	private Batch_Purger $batch_purger;

	/**
	 * @param Batch_Purger $batch_purger The batch purger.
	 */
	public function __construct( Batch_Purger $batch_purger ) {
		$this->batch_purger = $batch_purger;
	}

	/**
	 * Perform purges based on when settings change.
	 *
	 * @action solidwp/performance/settings/changed
	 *
	 * @param mixed[] $old_val The old value before saving.
	 * @param mixed[] $new_val The new saved value.
	 *
	 * @return void
	 */
	public function on_settings_change( array $old_val, array $new_val ): void {
		$old_lazy_load = Arr::get( $old_val, 'page_cache.lazy_loading.enabled' );
		$new_lazy_load = Arr::get( $new_val, 'page_cache.lazy_loading.enabled' );

		if ( $old_lazy_load === $new_lazy_load ) {
			return;
		}

		$this->batch_purger->queue_purge_all();
	}
}
