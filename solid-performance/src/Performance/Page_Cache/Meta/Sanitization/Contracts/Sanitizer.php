<?php
/**
 * The meta sanitizer contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Sanitization\Contracts;

use SolidWP\Performance\Page_Cache\Meta\Meta;

/**
 * @internal
 */
interface Sanitizer {

	/**
	 * Returns a sanitized Meta object.
	 *
	 * @param Meta $meta The Meta object.
	 *
	 * @return Meta
	 */
	public function sanitize( Meta $meta ): Meta;
}
