<?php
/**
 * The Image Class.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

/**
 * Helps with detecting image data where we may not have standard "WordPress" ways
 * to get this data.
 *
 * @package SolidWP\Performance
 */
final class Image {

	/**
	 * Memoization cache of IDs, indexed by their URL.
	 *
	 * @var array<string, int>
	 */
	private array $ids = [];

	/**
	 * Memoization cache of image metadata, indexed by their ID.
	 *
	 * @var array<int, mixed[]>
	 */
	private array $meta = [];

	/**
	 * Last resort to detect an image's size based on its URL.
	 *
	 * @param string $url The original image URL.
	 * @param int    $id An optional attachment ID.
	 *
	 * @return array{0: int, 1: int}|string An array with width/height values or the thumbnail size name.
	 */
	public function detect_size( string $url, int $id = 0 ) {
		$id = $id ?: $this->url_to_id( $url );

		if ( ! $id ) {
			return '';
		}

		$path = wp_parse_url( $url, PHP_URL_PATH );

		if ( ! $path ) {
			return '';
		}

		$file = basename( $path );

		if ( ! $file ) {
			return '';
		}

		// Try to prevent a query.
		$this->meta[ $id ] ??= wp_get_attachment_metadata( $id );

		$meta      = $this->meta[ $id ];
		$yearmonth = ltrim( wp_get_upload_dir()['subdir'], '/' );
		$full      = $meta['file'] ?? '';
		$original  = $meta['original_image'] ?? '';

		/*
		 * Check if this is the full-sized or original image.
		 *
		 * e.g.
		 * my-image.jpg
		 * my-image-scaled.jpg
		 * 2025/02/my-image.jpg
		 * 2025/02/my-image-scaled.jpg
		 * my-image-original-upload-name-if-scaled.jpg
		 */
		if (
			$file === $full ||
			"$yearmonth/$file" === $full ||
			$file === $original
		) {
			return [
				(int) ( $meta['width'] ?? 0 ),
				(int) ( $meta['height'] ?? 0 ),
			];
		}

		if ( empty( $meta['sizes'] ) ) {
			return '';
		}

		foreach ( $meta['sizes'] as $name => $sizes ) {
			if ( empty( $sizes['file'] ) || $file !== $sizes['file'] ) {
				continue;
			}

			// Try to return the width/height first to prevent more lookups.
			if ( isset( $sizes['width'], $sizes['height'] ) ) {
				return [
					(int) $sizes['width'],
					(int) $sizes['height'],
				];
			}

			// Otherwise, return the thumbnail size name.
			return $name;
		}

		return '';
	}

	/**
	 * Support getting an ID for image URLs with their thumbnail dimensions in the file name with
	 * a fallback to also check for the -scaled version.
	 *
	 * @param string $url The original attachment URL.
	 *
	 * @return int
	 */
	public function url_to_id( string $url ): int {
		if ( isset( $this->ids[ $url ] ) ) {
			return $this->ids[ $url ];
		}

		$full_image_url = $this->build_url( $url );

		if ( ! $full_image_url ) {
			return 0;
		}

		$id = attachment_url_to_postid( $full_image_url );

		// Fallback to check for a -scaled version of the full image.
		if ( ! $id ) {
			$id = attachment_url_to_postid( $this->build_url( $url, '-scaled' ) );
		}

		// Cache result.
		$this->ids[ $url ] = $id;

		return $id;
	}

	/**
	 * Remove the thumbnail dimensions portion of a URL to find the main URL that would be stored in
	 * the database.
	 *
	 * @param string $url The original URL.
	 * @param string $replacement The string to use when replacing the widthxheight portion of the URL.
	 *
	 * @return string
	 */
	private function build_url( string $url, string $replacement = '' ): string {
		$parsed_url = wp_parse_url( $url );

		if ( ! isset( $parsed_url['path'] ) ) {
			return '';
		}

		$path = $parsed_url['path'];

		// Replace the last occurrence of -WIDTHxHEIGHT before the file extension.
		$path = preg_replace( '/-\d+x\d+(?=\.[a-zA-Z0-9]+$)/', $replacement, $path, 1 );

		// Reconstruct the image URL.
		return isset( $parsed_url['scheme'], $parsed_url['host'] )
			? "{$parsed_url['scheme']}://{$parsed_url['host']}$path"
			: $path;
	}
}
