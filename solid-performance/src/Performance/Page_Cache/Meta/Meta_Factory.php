<?php
/**
 * A factory to create a Meta Value Object.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta;

use InvalidArgumentException;
use SolidWP\Performance\Http\Header_Factory;

/**
 * A factory to create a Meta Value Object.
 *
 * @see Meta
 *
 * @package SolidWP\Performance
 */
class Meta_Factory {

	/**
	 * @var Header_Factory
	 */
	private Header_Factory $header_factory;

	/**
	 * @param Header_Factory $header_factory The header factory.
	 */
	public function __construct( Header_Factory $header_factory ) {
		$this->header_factory = $header_factory;
	}

	/**
	 * Make a Meta Value Object.
	 *
	 * @param string $url The URL associated with the meta.
	 *
	 * @throws InvalidArgumentException If the $url is empty.
	 *
	 * @return Meta
	 */
	public function make( string $url ): Meta {
		return Meta::from(
			[
				'url'     => $url,
				'headers' => $this->header_factory->make(),
			]
		);
	}
}
