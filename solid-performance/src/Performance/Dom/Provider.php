<?php
/**
 * Registers DOM related functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Dom;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Dom\Modifiers\Image_Transformation_Modifier;
use SolidWP\Performance\Dom\Modifiers\Lazy_Load_Modifier;
use SolidWP\Performance\StellarWP\Pipeline\Pipeline;

/**
 * Registers DOM related functionality in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const PIPELINE = 'solid_performance.dom.modifier_pipeline';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_dom_modifier_pipeline();

		// Run the HTML through the pipeline before being rendered.
		add_filter(
			'solidwp/performance/cache/html',
			$this->container->callback( Modifier::class, 'modify' ),
			10,
			1
		);
	}

	/**
	 * Register the dom modifier pipeline steps.
	 *
	 * @return void
	 */
	private function register_dom_modifier_pipeline(): void {
		$this->container->singleton(
			self::PIPELINE,
			fn(): Pipeline => ( new Pipeline( $this->container ) )->through(
				// Register any dom modifier pipeline stages here.
				[
					Image_Transformation_Modifier::class,
					Lazy_Load_Modifier::class,
				]
			)
		);

		$this->container->when( Modifier::class )
						->needs( Pipeline::class )
						->give( static fn( $c ) => $c->get( self::PIPELINE ) );
	}
}
