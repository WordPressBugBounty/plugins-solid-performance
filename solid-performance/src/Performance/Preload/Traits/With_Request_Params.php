<?php
/**
 * Preload request parameters.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Traits;

use SolidWP\Performance\Preload\Contracts\Preloadable;

trait With_Request_Params {

	/**
	 * The Preload request parameters to bypass the cache.
	 *
	 * @return array<string, mixed>
	 */
	private function get_preload_cache_bust_params(): array {
		return [
			Preloadable::PRELOAD_CACHE_BUST_PARAM => true,
			Preloadable::PRELOAD_NO_CACHE_PARAM   => true,
		];
	}

	/**
	 * The Preload request parameters to trigger a preloader batch.
	 *
	 * @return array<string, mixed>
	 */
	private function get_preload_trigger_params(): array {
		return [
			Preloadable::PRELOAD_TRIGGER_PARAM  => true,
			Preloadable::PRELOAD_NO_CACHE_PARAM => uniqid(),
		];
	}
}
