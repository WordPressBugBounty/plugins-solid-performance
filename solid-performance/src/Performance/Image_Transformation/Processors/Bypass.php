<?php
/**
 * A bypass/null image processor that returns blank values or the original values for filtered variables.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation\Processors;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;

/**
 * A bypass/null image processor that returns blank values or the original values for filtered variables.
 *
 * @package SolidWP\Performance
 */
final class Bypass implements Processor {

	/**
	 * Return an empty URL.
	 *
	 * @param int $attachment_id The attachment ID.
	 * @param int $width The desired width to size the image as.
	 * @param int $height The desired height to size the image as.
	 *
	 * @return string
	 */
	public function get_image_url( int $attachment_id, int $width = 0, int $height = 0 ): string {
		return '';
	}

	/**
	 * Return the original URL.
	 *
	 * @see wp_get_attachment_metadata()
	 *
	 * @param string                                                                                                                                                                $url The current URL.
	 * @param array{width: int, height: int, file: string, sizes: array<string, array{file: string, width: int, height: int, mime-type: string}>, image_meta: array, filesize: int} $meta The attachment metadata.
	 *
	 * @return string
	 */
	public function filter_attachment_url( string $url, array $meta ): string {
		return $url;
	}

	/**
	 * Return the original sources.
	 *
	 * @param array{width: array{url: string, descriptor: string, value: int}} $sources One or more arrays of source data to include in the `srcset`.
	 * @param int                                                              $attachment_id Image attachment ID or 0.
	 *
	 * @return array{width: array{url: string, descriptor: string, value: int}}
	 */
	public function filter_srcset( array $sources, int $attachment_id ): array {
		return $sources;
	}

	/**
	 * Return an empty downsize array.
	 *
	 * @param int          $attachment_id The attachment ID.
	 * @param string|int[] $size Requested image size name or an array of width and height values in pixels (in that order).
	 *
	 * @return array{0: string, 1: int, 2: int, 3: bool} URL, width, height, is_intermediate.
	 */
	public function downsize_images( int $attachment_id, $size ): array {
		return [];
	}
}
