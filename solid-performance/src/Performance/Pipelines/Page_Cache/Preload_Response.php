<?php
/**
 * Handle Preload Responses.
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\Pipelines\Page_Cache;

use Closure;
use SolidWP\Performance\Pipelines\Page_Cache\Traits\With_Preloading;

use SolidWP\Performance\StellarWP\Pipeline\Contracts\Pipe;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Don't serve a cached version of a preload response.
 *
 * @package SolidWP\Performance
 */
class Preload_Response implements Pipe {

	use With_Preloading;

	/**
	 * {@inheritdoc}
	 */
	public function handle( $context, Closure $next ) {
		// Don't serve cached pages for a preload request.
		if ( $this->is_preload_request( $context->get_request() ) ) {
			return false;
		}

		return $next( $context );
	}
}
