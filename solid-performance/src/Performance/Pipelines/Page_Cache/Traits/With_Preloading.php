<?php
/**
 * Preloading specific methods.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Pipelines\Page_Cache\Traits;

use SolidWP\Performance\Http\Request;
use SolidWP\Performance\Preload\Contracts\Preloadable;
use SolidWP\Performance\StellarWP\Pipeline\Contracts\Pipe;

/**
 * @mixin Pipe
 */
trait With_Preloading {

	/**
	 * Check if this is a preload request.
	 *
	 * @param Request $request The current request.
	 *
	 * @return bool
	 */
	private function is_preload_request( Request $request ): bool {
		return str_starts_with(
			$request->query,
			sprintf( '%s=1', Preloadable::PRELOAD_CACHE_BUST_PARAM )
		);
	}
}
