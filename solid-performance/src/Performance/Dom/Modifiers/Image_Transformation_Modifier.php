<?php
/**
 * The Image Transformation DOM Modifier.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Dom\Modifiers;

use Closure;
use InvalidArgumentException;
use LogicException;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Dom\Contracts\Modifier;
use SolidWP\Performance\Image_Transformation\Image;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\DomCrawler\Crawler;

/**
 * Replaces inline style background URLs with their transformed version.
 *
 * @package SolidWP\Performance
 */
final class Image_Transformation_Modifier implements Modifier {

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @var Image
	 */
	private Image $image;

	/**
	 * @param Config          $config The config object.
	 * @param LoggerInterface $logger The logger.
	 * @param Image           $image The image object.
	 */
	public function __construct(
		Config $config,
		LoggerInterface $logger,
		Image $image
	) {
		$this->config = $config;
		$this->logger = $logger;
		$this->image  = $image;
	}

	/**
	 * Whether this pipeline step is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return (bool) $this->config->get( 'page_cache.image_transformation.enabled' );
	}

	/**
	 * Return true immediately if we're enabled, otherwise pass the current result to the next
	 * stage.
	 *
	 * @param bool    $enabled Whether the previous stage is enabled.
	 * @param Closure $next The closer passed to the next stage.
	 *
	 * @return bool
	 */
	public function enabled( bool $enabled, Closure $next ): bool {
		return $this->is_enabled() ?: $next( $enabled );
	}

	/**
	 * Find elements that have a style tag which contains a `url()` data tag and attempt to
	 * replace the image URL with our transformed image URl.
	 *
	 * @param Crawler $crawler The Symfony Crawler instance.
	 * @param Closure $next The next closure to pass in the pipeline.
	 *
	 * @throws InvalidArgumentException When current node is empty.
	 * @throws LogicException If the CssSelector Component is not available.
	 *
	 * @return Crawler
	 */
	public function modify( Crawler $crawler, Closure $next ): Crawler {
		if ( ! $this->is_enabled() ) {

			$this->logger->debug( 'Image_Transformation_Modifier is marked as disabled. Skipping...' );

			return $next( $crawler );
		}

		// Iterate through all style elements.
		$crawler->filter( 'style' )->each(
			function ( Crawler $node ) {
				$style = $node->text( '', false );

				// Match CSS property:value pairs with background or background-image.
				if ( ! preg_match_all( '/(background|background-image)\s*:\s*(?<values_with_urls>[^;]*url\([^)]*\)[^;]*)\s*;?/', $style, $matches ) ) {
					return;
				}

				$values_with_urls = $matches['values_with_urls'];

				$update_style = false;

				foreach ( $values_with_urls as $property_value ) {
					// Extract URLs from between all `url()` strings that start with http or https.
					if ( ! preg_match_all( '/url\s*\(\s*(["\']?)(https?:\/\/[^ ]*?)\1\s*\)/i', $property_value, $url_matches ) ) {
						continue;
					}

					$urls = $url_matches[2];

					foreach ( $urls as $url ) {
						$attachment_id = $this->image->url_to_id( $url );

						if ( $attachment_id === 0 ) {
							$this->logger->warning(
								'Unable to find attachment_id for CSS style background image: {image_url}',
								[
									'image_url' => $url,
									'style'     => $style,
								]
							);

							continue;
						}

						$size       = $this->image->detect_size( $url, $attachment_id );
						$image_data = image_downsize( $attachment_id, $size );

						if ( ! $image_data ) {
							$this->logger->warning(
								'Unable to find CSS background image_downsize data',
								[
									'attachment_id' => $attachment_id,
									'image_url'     => $url,
								]
							);

							continue;
						}

						$new_image_url = $image_data[0] ?? '';

						// Replace the old URL with the transformed URL.
						$style = str_replace( $url, $new_image_url, $style );

						$update_style = true;
					}
				}

				if ( ! $update_style ) {
					return;
				}

				$current = $node->getNode( 0 );

				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$current->nodeValue = $style;
			}
		);

		// Iterate through all elements with a style tag that contains a url() data type.
		$crawler->filter( '[style*="url("]' )->each(
			function ( Crawler $node ) {
				$style = (string) $node->attr( 'style' );

				// Extract a background image URL that starts with http or https.
				if ( ! preg_match( '/url\s*\(\s*(["\']?)(https?:\/\/.*?)\1\s*\)/i', $style, $image_urls ) ) {
					return;
				}

				$original_url = $image_urls[2] ?? '';

				$classes = (string) $node->attr( 'class' );

				// Extract attachment ID, try to avoid a database query.
				if ( preg_match( '/wp-image-(\d+)/', $classes, $attachment_ids ) ) {
					$attachment_id = (int) $attachment_ids[1];
				} elseif ( preg_match( '/attachment-(\d+)/', $classes, $attachment_ids ) ) {
					$attachment_id = (int) $attachment_ids[1];
				} elseif ( $node->attr( 'data-id' ) ) {
					$attachment_id = (int) $node->attr( 'data-id' );
				} else {
					$attachment_id = $this->image->url_to_id( $original_url );
				}

				if ( $attachment_id === 0 ) {
					$this->logger->warning(
						'Unable to find attachment_id for {image_url}',
						[
							'image_url' => $original_url,
						]
					);

					return;
				}

				// Extract thumbnail size or try to detect it from the URL.
				$size = preg_match( '/size-([\w-]+)/', $classes, $sizes )
					? $sizes[1]
					: $this->image->detect_size( $original_url, $attachment_id );

				$image_data = image_downsize( $attachment_id, $size );

				if ( ! $image_data ) {
					$this->logger->warning(
						'Unable to find image_downsize data',
						[
							'attachment_id' => $attachment_id,
							'image_url'     => $original_url,
						]
					);

					return;
				}

				$new_image_url = $image_data[0] ?? '';

				// Replace the old URL with the transformed URL.
				$style = str_replace( $original_url, $new_image_url, $style );

				$this->logger->debug(
					'Image URL Transformed',
					[
						'original_image_url' => $original_url,
						'new_image_url'      => $new_image_url,
						'style'              => $style,
					]
				);

				$current = $node->getNode( 0 );
				$current->setAttribute( 'style', $style );
			}
		);

		return $next( $crawler );
	}
}
