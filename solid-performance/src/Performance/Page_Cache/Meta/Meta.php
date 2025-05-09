<?php
/**
 * The meta value object.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta;

use InvalidArgumentException;
use SolidWP\Performance\Http\Header;

/**
 * The meta value object.
 *
 * @package SolidWP\Performance
 */
final class Meta {

	/**
	 * The URL this meta is associated with.
	 *
	 * @var string
	 */
	public string $url;

	/**
	 * The header object which contains the response headers.
	 *
	 * @var Header
	 */
	public Header $headers;

	/**
	 * @param string $url The URL this meta is associated with.
	 * @param Header $headers The header object which contains the response headers.
	 *
	 * @throws InvalidArgumentException If the $url argument is empty.
	 */
	private function __construct( string $url, Header $headers ) {
		if ( ! $url ) {
			throw new InvalidArgumentException( 'The $url argument cannot be empty' );
		}

		$this->url     = $url;
		$this->headers = $headers;
	}

	/**
	 * Creates the value object.
	 *
	 * @param array{url: string, headers: Header|array<string, string[]>} $data The meta data.
	 *
	 * @throws InvalidArgumentException If the $url argument is empty.
	 *
	 * @return self
	 */
	public static function from( array $data ): self {
		$headers = $data['headers'];

		if ( ! $headers instanceof Header ) {
			$headers = ( new Header() )->replace( $headers );
		}

		return new self(
			$data['url'],
			$headers
		);
	}

	/**
	 * Convert the value object to an array.
	 *
	 * @return array{url: string, headers: array<string, string[]>}
	 */
	public function to_array(): array {
		return [
			'url'     => $this->url,
			'headers' => $this->headers->all(),
		];
	}
}
