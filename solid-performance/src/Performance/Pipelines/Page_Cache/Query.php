<?php
/**
 * Prevents requests with a query from being cached.
 *
 * @since 0.1.0
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
 * Prevent a request with a query from being cached.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
class Query implements Pipe {

	use With_Preloading;

	/**
	 * {@inheritdoc}
	 */
	public function handle( $context, Closure $next ) {
		$query = $context->get_request()->query;

		// Allow a preload request to be passed down the chain.
		if ( $this->is_preload_request( $context->get_request() ) ) {
			return $next( $context );
		}

		if ( strlen( $query ) > 0 ) {
			return false;
		}

		return $next( $context );
	}
}
