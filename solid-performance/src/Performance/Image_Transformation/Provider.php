<?php
/**
 * Registers Image Transformation functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Image_Transformation\Contracts\Processor;
use SolidWP\Performance\Image_Transformation\Decorators\Memoized;
use SolidWP\Performance\Image_Transformation\Processors\Bypass;
use SolidWP\Performance\Image_Transformation\Processors\Cloudflare;

/**
 * Registers Image Transformation functionality in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_settings_listeners();
		$this->register_processors();
		$this->register_transformer();
	}

	/**
	 * Register a settings listener to purge the cache on image transformation changes and
	 * to validate an image processor works before it gets enabled.
	 *
	 * @return void
	 */
	private function register_settings_listeners(): void {
		add_action(
			'solidwp/performance/settings/changed',
			$this->container->callback( Settings_Listener::class, 'on_settings_change' ),
			10,
			2,
		);

		/**
		 * Filters whether Settings Image Transformation Validation is enabled.
		 *
		 * @param bool $should_validate Whether we should validate.
		 */
		$should_validate = apply_filters( 'solidwp/performance/image_transformation/should_validate', true );

		if ( $should_validate ) {
			add_filter(
				'solidwp/performance/settings/before_save',
				$this->container->callback( Settings_Validator::class, 'validate' ),
				10,
				2
			);
		}
	}
	/**
	 * Register image transformation processors container definitions.
	 *
	 * @return void
	 */
	private function register_processors(): void {
		$this->container->when( Cloudflare::class )
						->needs( '$options' )
						->give(
							static fn(): array =>
							/**
							 * The default options to use for Cloudflare's Transform via URL.
							 *
							 * @link https://developers.cloudflare.com/images/transform-images/transform-via-url/#options
							 *
							 * @param array<string, int|string> $options The Cloudflare options.
							 */
							apply_filters(
								'solidwp/performance/image_transformation/processor/cloudflare/options',
								[
									'w'   => 0,
									'h'   => 0,
									'dpr' => 1,
									'fit' => 'cover',
									'f'   => 'auto',
									'q'   => 85,
								]
							)
						);
	}

	/**
	 * Registers the Image Transformer definitions in the container.
	 *
	 * @return void
	 */
	private function register_transformer(): void {
		$config = $this->container->get( Config::class );

		// Fallback to the Bypass processor in the event we are unable to find a processor.
		$processor_class = Processor_Type::tryFrom( (string) $config->get( 'page_cache.image_transformation.processor' ) ) ?? Bypass::class;

		// Wrap the processor in the Memoized decorator for an in-memory cache.
		$this->container->bindDecorators(
			Processor::class,
			[
				Memoized::class,
				$processor_class,
			]
		);

		$this->container->when( Transformer::class )
						->needs( '$mime_types' )
						->give(
							static fn(): array =>
							/**
							 * Filter the allowed mime types an attachment must have in order to be transformed
							 * by the image transformer.
							 *
							 * @param string[] $mime_types The mime types.
							 */
							apply_filters(
								'solidwp/performance/image_transformation/mime_types',
								[
									'image/jpeg',
									'image/png',
									'image/gif',
									'image/webp',
								]
							)
						);

		$this->container->singleton( Transformer::class, Transformer::class );

		// Note we still need the definitions in case any other classes use Transformer as a dependency.
		// If this isn't enabled, bail here to prevent it from running.
		if ( ! $config->get( 'page_cache.image_transformation.enabled' ) ) {
			return;
		}

		// Run on template_redirect to only transform image URLs on the front-end.
		add_action(
			'template_redirect',
			function () {
				add_filter(
					'wp_get_attachment_url',
					function ( $url, $attachment_id ) {
						return $this->container->get( Transformer::class )
												->filter_attachment_url( (string) $url, (int) $attachment_id );
					},
					60,
					2
				);

				add_filter(
					'image_downsize',
					function ( $downsize, $attachment_id, $size ) {
						return $this->container->get( Transformer::class )->filter_image_downsize( $downsize, (int) $attachment_id, $size );
					},
					60,
					3
				);

				add_filter(
					'wp_calculate_image_srcset',
					function ( $sources, $sizes, $image_src, $image_meta, $attachment_id ): array {
						return $this->container->get( Transformer::class )->filter_srcset( (array) $sources, (int) $attachment_id );
					},
					60,
					5
				);

				add_filter(
					'wp_content_img_tag',
					function ( $image_markup, $context, $attachment_id ) {
						return $this->container->get( Transformer::class )->filter_img_tag_markup( (string) $image_markup, (int) $attachment_id );
					},
					60,
					3
				);
			},
			20
		);
	}
}
