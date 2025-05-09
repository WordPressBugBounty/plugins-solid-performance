<?php
/**
 * Handle Preload Requests.
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
 * Allow a preload request to be cached.
 *
 * @package SolidWP\Performance
 */
class Preload_Request implements Pipe {

	use With_Preloading;

	/**
	 * {@inheritdoc}
	 */
	public function handle( $context, Closure $next ) {
		// Allow a preload request to be cached.
		if ( $this->is_preload_request( $context->get_request() ) ) {
			return true;
		}

		return $next( $context );
	}
}
