<?php
/**
 * Listen for settings changes for image transformation and purge the
 * cache.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Page_Cache\Purge\Batch\Batch_Purger;
use SolidWP\Performance\StellarWP\Arrays\Arr;

/**
 * Listen for settings changes for image transformation and purge the
 * cache.
 *
 * @package SolidWP\Performance
 */
final class Settings_Listener {

	/**
	 * @var Batch_Purger
	 */
	private Batch_Purger $purger;

	/**
	 * @param Batch_Purger $batch_purger The batch purger.
	 */
	public function __construct( Batch_Purger $batch_purger ) {
		$this->purger = $batch_purger;
	}

	/**
	 * Purge the cache if image transformation options changed.
	 *
	 * @action solidwp/performance/settings/changed
	 *
	 * @param mixed[] $old_settings The old settings array.
	 * @param mixed[] $new_settings The new settings array.
	 *
	 * @return void
	 */
	public function on_settings_change( array $old_settings, array $new_settings ): void {
		$old_status = Arr::get( $old_settings, 'page_cache.image_transformation.enabled' );
		$new_status = Arr::get( $new_settings, 'page_cache.image_transformation.enabled' );

		if ( $old_status !== $new_status ) {
			$this->purger->queue_purge_all();

			return;
		}

		$old_processor = Arr::get( $old_settings, 'page_cache.image_transformation.processor' );
		$new_processor = Arr::get( $new_settings, 'page_cache.image_transformation.processor' );

		if ( $old_processor !== $new_processor ) {
			$this->purger->queue_purge_all();
		}
	}
}
