<?php
/**
 * The Cloudflare Image Processor.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation\Processors;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;
use SolidWP\Performance\Image_Transformation\Processors\Traits\With_WordPress_Defaults;

/**
 * The Cloudflare Image Processor.
 *
 * @link https://developers.cloudflare.com/images/transform-images/transform-via-url/#options
 *
 * @package SolidWP\Performance
 */
final class Cloudflare implements Processor {

	use With_WordPress_Defaults;

	/**
	 * @var array<string, int|string>
	 */
	private array $options;

	/**
	 * @param array<string, mixed> $options The default Cloudflare URL options to use.
	 */
	public function __construct( array $options ) {
		$this->options = $options;
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
		$url = (string) wp_get_attachment_url( $attachment_id );

		$params = preg_match( '#/([a-z]+=[^,/]+(?:,[a-z]+=[^,/]+)*)/#', $url, $matches );

		if ( ! $params ) {
			return $url;
		}

		$params = array_filter(
			wp_parse_args(
				[
					'w' => $width,
					'h' => $height,
				],
				$this->options
			)
		);

		$options = http_build_query( $params, '', ',' );
		$url     = str_replace( $matches[1], $options, $url );

		return esc_url_raw( $url );
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
		$parsed = wp_parse_url( $url );

		if ( ! isset( $parsed['path'] ) ) {
			return $url;
		}

		$options = array_filter(
			wp_parse_args(
				[
					'w' => $meta['width'] ?? 0,
					'h' => $meta['height'] ?? 0,
				],
				$this->options
			)
		);

		$options = http_build_query( $options, '', ',' );

		return sprintf(
			'%s://%s/cdn-cgi/image/%s/%s',
			$parsed['scheme'],
			$parsed['host'],
			$options,
			ltrim( $parsed['path'], '/\\' )
		);
	}
}
