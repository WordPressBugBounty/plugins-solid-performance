<?php
/**
 * A Factory to try to create an Image Processor instance.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Container;
use SolidWP\Performance\Image_Transformation\Contracts\Processor;

/**
 * A Factory to try to create an Image Processor instance.
 *
 * @see Processor_Type::names()
 *
 * @package SolidWP\Performance
 */
final class Processor_Factory {

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * @param Container $container The container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Attempt to make an Image Processor instance.
	 *
	 * @see Processor_Type::names()
	 *
	 * @note Ensure your processor's container definitions have already been loaded.
	 *
	 * @param string $processor_name A valid Processor Name.
	 *
	 * @return Processor|null
	 */
	public function make( string $processor_name ): ?Processor {
		$processor_class = Processor_Type::tryFrom( $processor_name );

		return $processor_class ? $this->container->get( $processor_class ) : null;
	}
}
