<?php
/**
 * A collection of meta sanitizers.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Sanitization;

use Countable;
use SolidWP\Performance\Page_Cache\Meta\Meta;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Contracts\Sanitizer;

/**
 * A collection of meta sanitizers.
 *
 * @package SolidWP\Performance
 */
final class Collection implements Countable {

	/**
	 * @var Sanitizer[]
	 */
	private array $sanitizers;

	/**
	 * @param Sanitizer[] $sanitizers The meta sanitizers.
	 */
	public function __construct( array $sanitizers ) {
		$this->sanitizers = $sanitizers;
	}

	/**
	 * Run the Meta through the collection of sanitizers.
	 *
	 * @param Meta $meta The Meta object.
	 *
	 * @return Meta The sanitized Meta object.
	 */
	public function sanitize( Meta $meta ): Meta {
		return array_reduce(
			$this->sanitizers,
			static fn( Meta $carry, Sanitizer $sanitizer ) => $sanitizer->sanitize( $carry ),
			$meta
		);
	}

	/**
	 * The number of sanitizers in the collection.
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->sanitizers );
	}
}
