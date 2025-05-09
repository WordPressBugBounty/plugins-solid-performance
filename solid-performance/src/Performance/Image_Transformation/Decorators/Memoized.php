<?php
/**
 * The Memoized Image Processor Decorator.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation\Decorators;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;
use SolidWP\Performance\StellarWP\Memoize\Contracts\MemoizerInterface;

/**
 * The Memoized Image Processor Decorator.
 *
 * Takes the underlying Image Processor and caches its responses in memory.
 *
 * @package SolidWP\Performance
 */
final class Memoized implements Processor {

	/**
	 * @var Processor
	 */
	private Processor $processor;

	/**
	 * @var MemoizerInterface
	 */
	private MemoizerInterface $memoizer;

	/**
	 * @param Processor         $processor The configured Image Processor.
	 * @param MemoizerInterface $memoizer The memoizer.
	 */
	public function __construct(
		Processor $processor,
		MemoizerInterface $memoizer
	) {
		$this->processor = $processor;
		$this->memoizer  = $memoizer;
	}

	/**
	 * Get the underlying processor the decorator is using.
	 *
	 * @return Processor
	 */
	public function processor(): Processor {
		return $this->processor;
	}

	/**
	 * Generate a transformed image URL based on attachment ID and provided width and height.
	 *
	 * @param int $attachment_id The attachment ID.
	 * @param int $width The desired width to size the image as.
	 * @param int $height The desired height to size the image as.
	 *
	 * @return string
	 */
	public function get_image_url( int $attachment_id, int $width = 0, int $height = 0 ): string {
		$key = $this->build_key( func_get_args() );

		$url = $this->memoizer->get( $key );

		if ( $url === null ) {
			$url = $this->processor->get_image_url( $attachment_id, $width, $height );

			$this->memoizer->set( $key, $url );
		}

		return $url;
	}

	/**
	 * Modify the image URL to use the transformed image path.
	 *
	 * @see wp_get_attachment_metadata()
	 *
	 * @param string                                                                                                                                                                $url The current URL.
	 * @param array{width: int, height: int, file: string, sizes: array<string, array{file: string, width: int, height: int, mime-type: string}>, image_meta: array, filesize: int} $meta The attachment metadata.
	 *
	 * @return string
	 */
	public function filter_attachment_url( string $url, array $meta ): string {
		$key = $this->build_key( func_get_args() );

		$attachment_url = $this->memoizer->get( $key );

		if ( $attachment_url === null ) {
			$attachment_url = $this->processor->filter_attachment_url( $url, $meta );

			$this->memoizer->set( $key, $attachment_url );
		}

		return $attachment_url;
	}

	/**
	 * Replace the srcset URLs with our transformed versions.
	 *
	 * @param array{width: array{url: string, descriptor: string, value: int}} $sources One or more arrays of source data to include in the `srcset`.
	 * @param int                                                              $attachment_id Image attachment ID or 0.
	 *
	 * @return array{width: array{url: string, descriptor: string, value: int}}
	 */
	public function filter_srcset( array $sources, int $attachment_id ): array {
		$key = $this->build_key( func_get_args() );

		$srcset = $this->memoizer->get( $key );

		if ( $srcset === null ) {
			$srcset = $this->processor->filter_srcset( $sources, $attachment_id );

			$this->memoizer->set( $key, $srcset );
		}

		return $srcset;
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
		$key = $this->build_key( func_get_args() );

		$images = $this->memoizer->get( $key );

		if ( $images === null ) {
			$images = $this->processor->downsize_images( $attachment_id, $size );

			$this->memoizer->set( $key, $images );
		}

		return $images;
	}

	/**
	 * Build a unique key based on the method arguments.
	 *
	 * @param mixed ...$args The method arguments to build the key from.
	 *
	 * @return string
	 */
	private function build_key( ...$args ): string {
		return md5( wp_json_encode( $args ) );
	}
}
