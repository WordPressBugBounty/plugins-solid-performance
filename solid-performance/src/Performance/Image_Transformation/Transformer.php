<?php
/**
 * The Image Transformer to update image URLs in multiple locations
 * to use the edge image URL for the underlying processor.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;

/**
 * The Image Transformer to update image URLs in multiple locations
 * to use the edge image URL for the underlying processor.
 *
 * @package SolidWP\Performance
 */
final class Transformer {

	/**
	 * A memoization cache for already processed attachment URLs.
	 *
	 * @var array<int, string>
	 */
	private array $attachments = [];

	/**
	 * The Underlying Image Processor to use.
	 *
	 * @var Processor
	 */
	private Processor $processor;

	/**
	 * The attachment mime types we will allow to be processed.
	 *
	 * @var string[]
	 */
	private array $mime_types;

	/**
	 * @param Processor $processor The transformation processor to use.
	 * @param string[]  $mime_types The list of allowed mime types for an attachment to be processed.
	 */
	public function __construct(
		Processor $processor,
		array $mime_types
	) {
		$this->processor  = $processor;
		$this->mime_types = $mime_types;
	}

	/**
	 * Get the transformed image URL with the correct dimensions.
	 *
	 * @param int $attachment_id The attachment ID.
	 * @param int $width The width from the WordPress thumbnail size.
	 * @param int $height The height from the WordPress thumbnail size.
	 *
	 * @return string
	 */
	public function get_image_url( int $attachment_id, int $width = 0, int $height = 0 ): string {
		return $this->processor->get_image_url( $attachment_id, $width, $height );
	}

	/**
	 * Modify the attachment URL to use the transformed image path.
	 *
	 * @filter wp_get_attachment_url
	 *
	 * @param string $url The current URL.
	 * @param int    $attachment_id The attachment id.
	 *
	 * @return string
	 */
	public function filter_attachment_url( string $url, int $attachment_id ): string {
		if ( isset( $this->attachments[ $attachment_id ] ) ) {
			return $this->attachments[ $attachment_id ];
		}

		if ( $this->is_image( $attachment_id ) ) {
			$meta = wp_get_attachment_metadata( $attachment_id );

			if ( $meta ) {
				$url = $this->processor->filter_attachment_url( $url, $meta );
			}
		}

		// Cache both successful and unsuccessful URLs.
		$this->attachments[ $attachment_id ] = $url;

		return $url;
	}

	/**
	 * Modify the downsized image URLs.
	 *
	 * @filter image_downsize
	 *
	 * @param bool|array{0: string, 1: int, 2: int, 3: bool} $downsize Whether to short-circuit the image downsize.
	 * @param int                                            $attachment_id The attachment ID.
	 * @param string|int[]                                   $size Requested image size name or an array of width and height values in pixels (in that order).
	 *
	 * @return array{0: string, 1: int, 2: int, 3: bool}|bool
	 */
	public function filter_image_downsize( $downsize, int $attachment_id, $size ) {
		$data = $this->processor->downsize_images( $attachment_id, $size );

		return $data ?: $downsize;
	}

	/**
	 * Update each image size by using the main image but with dimensions.
	 *
	 * @filter wp_calculate_image_srcset
	 *
	 * @param array{width: array{url: string, descriptor: string, value: int}} $sources One or more arrays of source data to include in the `srcset`.
	 * @param int                                                              $attachment_id Image attachment ID or 0.
	 *
	 * @return array{width: array{url: string, descriptor: string, value: int}}
	 */
	public function filter_srcset( array $sources, int $attachment_id ): array {
		return $this->processor->filter_srcset( $sources, $attachment_id );
	}

	/**
	 * Replace the image src with our transformed URL as this runs early and has not had its URL replace.
	 *
	 * @filter wp_content_img_tag
	 *
	 * @param string $image_markup Full img tag with attributes that will replace the source img tag.
	 * @param int    $attachment_id The image attachment ID. May be 0 in case the image is not an attachment.
	 *
	 * @return string
	 */
	public function filter_img_tag_markup( string $image_markup, int $attachment_id ): string {
		if ( ! $attachment_id ) {
			return $image_markup;
		}

		$image_src = preg_match( '/src="([^"]+)"/', $image_markup, $match_src ) ? $match_src[1] : '';
		$width     = preg_match( '/ width=["\']([0-9]+)["\']/', $image_markup, $match_width ) ? (int) $match_width[1] : 0;
		$height    = preg_match( '/ height=["\']([0-9]+)["\']/', $image_markup, $match_height ) ? (int) $match_height[1] : 0;

		$new_image_src = $this->get_image_url( $attachment_id, $width, $height );

		if ( ! $new_image_src ) {
			return $image_markup;
		}

		return str_replace( $image_src, $new_image_src, $image_markup );
	}

	/**
	 * Whether this attachment is an image and in our allowed mime types.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return bool
	 */
	private function is_image( int $attachment_id ): bool {
		$mime_type = get_post_mime_type( $attachment_id );

		if ( ! $mime_type ) {
			return false;
		}

		return in_array( $mime_type, $this->mime_types, true );
	}
}
