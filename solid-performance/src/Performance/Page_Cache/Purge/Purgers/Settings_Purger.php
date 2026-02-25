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
	 * @var array<string, int>
	 */
	private array $watch_list_keys;

	/**
	 * @param Batch_Purger $batch_purger The batch purger.
	 * @param string[]     $watch_list  List of dot-notated paths to monitor.
	 */
	public function __construct(
		Batch_Purger $batch_purger,
		array $watch_list
	) {
		$this->batch_purger    = $batch_purger;
		$this->watch_list_keys = array_flip( $watch_list );
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
		$old_flat = Arr::dot( $old_val );
		$new_flat = Arr::dot( $new_val );

		$changes = array_diff_assoc( $new_flat, $old_flat );

		if ( empty( array_intersect_key( $changes, $this->watch_list_keys ) ) ) {
			return;
		}

		$this->batch_purger->queue_purge_all();
	}
}
