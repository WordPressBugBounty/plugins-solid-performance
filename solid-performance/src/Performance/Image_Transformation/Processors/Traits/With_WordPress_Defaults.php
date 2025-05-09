<?php
/**
 * Processors that are relying on the existing WordPress dimension for
 * srcset and downsized images can utilize this trait.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation\Processors\Traits;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;

/**
 * @mixin Processor
 */
trait With_WordPress_Defaults {

	/**
	 * Replace the srcset URLs with our transformed versions.
	 *
	 * @param array<int, array{url: string, descriptor: string, value: int}> $sources One or more arrays of source data to include in the `srcset`.
	 * @param int                                                            $attachment_id Image attachment ID or 0.
	 *
	 * @return array<int, array{url: string, descriptor: string, value: int}>
	 */
	public function filter_srcset( array $sources, int $attachment_id ): array {
		foreach ( $sources as &$source ) {
			if ( 'w' !== $source['descriptor'] ) {
				continue;
			}

			$url = $this->get_image_url( $attachment_id, $source['value'] );

			if ( $url === $source['url'] ) {
				continue;
			}

			$source['url'] = $url;
		}

		return $sources;
	}

	/**
	 * Replace downsized image URLs with their transformed versions.
	 *
	 * @param int          $attachment_id The attachment ID.
	 * @param string|int[] $size Requested image size name or an array of width and height values in pixels (in that order).
	 *
	 * @return array{0: string, 1: int, 2: int, 3: bool} URL, width, height, is_intermediate.
	 */
	public function downsize_images( int $attachment_id, $size ): array {
		$is_img = wp_attachment_is_image( $attachment_id );

		if ( empty( $is_img ) ) {
			return [];
		}

		if ( is_string( $size ) ) {
			$meta = wp_get_attachment_metadata( $attachment_id );

			if ( 'full' === $size ) {
				$size_data = [
					'width'  => $meta['width'] ?? 0,
					'height' => $meta['height'] ?? 0,
				];
			} else {
				$size_data = $meta['sizes'][ $size ] ?? [];
			}

			if ( empty( $size_data ) ) {
				return [];
			}

			$width  = $size_data['width'] ?? 0;
			$height = $size_data['height'] ?? 0;
		} else {
			[ $width, $height ] = $size;
		}

		$is_intermediate = false;
		$intermediate    = image_get_intermediate_size( $attachment_id, $size );

		if ( $intermediate ) {
			$is_intermediate = true;
		}

		// We have the actual image size, but might need to further constrain it if content_width is narrower.
		[ $width, $height ] = image_constrain_size_for_editor( $width, $height, $size );

		$new_url = $this->get_image_url( $attachment_id, $width, $height );

		return [
			$new_url,
			$width,
			$height,
			$is_intermediate,
		];
	}
}
