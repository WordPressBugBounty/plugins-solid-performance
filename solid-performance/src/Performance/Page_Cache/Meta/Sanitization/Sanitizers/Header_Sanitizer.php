<?php
/**
 * Sanitizes meta response headers to remove any headers from our deny list.
 *
 * @package SolidWP\Performance
 */

declare(strict_types=1);

namespace SolidWP\Performance\Page_Cache\Meta\Sanitization\Sanitizers;

use SolidWP\Performance\Page_Cache\Meta\Meta;
use SolidWP\Performance\Page_Cache\Meta\Provider;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Contracts\Sanitizer;

/**
 * Sanitizes meta response headers to remove any headers from our deny list.
 *
 * @see Provider::register_sanitizers()
 *
 * @package SolidWP\Performance
 */
final class Header_Sanitizer implements Sanitizer {

	/**
	 * A list of response headers to never store or serve.
	 *
	 * @var string[]
	 */
	private array $denied_headers;

	/**
	 * @param string[] $denied_headers A list of response headers to never store or serve.
	 */
	public function __construct( array $denied_headers ) {
		$this->denied_headers = array_map( 'strtolower', $denied_headers );
	}

	/**
	 * Remove any headers present from the deny list.
	 *
	 * @param Meta $meta The Meta object.
	 *
	 * @return Meta
	 */
	public function sanitize( Meta $meta ): Meta {
		$headers = $meta->headers;
		$allowed = $headers->except( ...$this->denied_headers );

		$meta->headers = $headers->replace( $allowed );

		return $meta;
	}
}
