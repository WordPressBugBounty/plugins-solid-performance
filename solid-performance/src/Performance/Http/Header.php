<?php
/**
 * Holds HTTP headers.
 *
 * @see Header_Factory
 *
 * @since 1.0.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Http;

use SolidWP\Performance\StellarWP\Arrays\Arr;

/**
 * Holds HTTP headers.
 *
 * @see Header_Factory
 *
 * @since 1.0.0
 *
 * @package SolidWP\Performance
 */
class Header {

	protected const UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	protected const LOWER = '-abcdefghijklmnopqrstuvwxyz';

	/**
	 * A list of response headers indexed by their header name.
	 *
	 * @var array<string, string[]>
	 */
	private array $headers;

	/**
	 * @param  string[] $headers The list of raw HTTP headers.
	 */
	public function __construct( array $headers = [] ) {
		if ( $headers ) {
			$this->normalize_headers( $headers );
		} else {
			$this->headers = [];
		}
	}

	/**
	 * Returns headers.
	 *
	 * @param string|null $name The name of the header, or if null, all headers.
	 *
	 * @return array<string, string[]>
	 */
	public function all( ?string $name = null ): array {
		if ( null !== $name ) {
			return $this->headers[ strtr( $name, self::UPPER, self::LOWER ) ] ?? [];
		}

		return $this->headers;
	}

	/**
	 * Returns only the specified headers.
	 *
	 * @param string ...$keys The header names to include.
	 *
	 * @return array<string, string[]>
	 */
	public function only( string ...$keys ): array {
		$included_keys = array_map(
			static fn( $name ) => strtr( $name, self::UPPER, self::LOWER ),
			$keys
		);

		return Arr::only( $this->headers, $included_keys );
	}


	/**
	 * Returns all headers except the ones provided.
	 *
	 * @param string ...$keys The header names to exclude.
	 *
	 * @return array<string, string[]>
	 */
	public function except( string ...$keys ): array {
		$excluded_keys = array_map(
			static fn( $name ) => strtr( $name, self::UPPER, self::LOWER ),
			$keys
		);

		return Arr::except( $this->headers, $excluded_keys );
	}

	/**
	 * Remove all existing headers and replace with the provided array.
	 *
	 * @param array<string, string[]> $headers The new headers to use.
	 *
	 * @return $this
	 */
	public function replace( array $headers ): self {
		$this->headers = [];

		foreach ( $headers as $name => $values ) {
			$this->set( $name, $values );
		}

		return $this;
	}

	/**
	 * Returns true if the HTTP header is defined.
	 *
	 * @param  string $name The header name.
	 *
	 * @return bool
	 */
	public function has( string $name ): bool {
		return array_key_exists( strtr( $name, self::UPPER, self::LOWER ), $this->all() );
	}

	/**
	 * Returns the first header by name.
	 *
	 * @param  string $name The header name.
	 *
	 * @return string|null
	 */
	public function get( string $name ): ?string {
		$headers = $this->all( $name );

		if ( ! $headers ) {
			return null;
		}

		if ( null === $headers[0] ) {
			return null;
		}

		return implode( ', ', $headers );
	}

	/**
	 * Returns true if the given HTTP header contains the given value.
	 *
	 * @param string $name The header name.
	 * @param string $value The header value to look for.
	 */
	public function contains( string $name, string $value ): bool {
		$header = $this->get( $name );

		if ( null === $header ) {
			return false;
		}

		return str_contains( $header, $value );
	}

	/**
	 * Returns true if the given HTTP header starts with the given value.
	 *
	 * @param  string $name   The header name.
	 * @param  string $value  The header value to look for.
	 *
	 * @return bool
	 */
	public function starts_with( string $name, string $value ): bool {
		$headers = $this->all( $name );

		if ( ! $headers ) {
			return false;
		}

		foreach ( $headers as $header_value ) {
			if ( str_starts_with( strtolower( $header_value ), strtolower( $value ) ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Sets a header by name.
	 *
	 * @param  string               $name     The header name.
	 * @param  string|string[]|null $values   The value or an array of values.
	 * @param  bool                 $replace  Whether to replace the actual value or not (true by
	 *     default).
	 */
	public function set( string $name, $values, bool $replace = true ): void {
		$name = strtr( $name, self::UPPER, self::LOWER );

		if ( is_array( $values ) ) {
			$values = array_values( $values );

			if ( true === $replace || ! isset( $this->headers[ $name ] ) ) {
				$this->headers[ $name ] = $values;
			} else {
				$this->headers[ $name ] = array_merge( $this->headers[ $name ], $values );
			}
		} elseif ( true === $replace || ! isset( $this->headers[ $name ] ) ) {
				$this->headers[ $name ] = [ $values ];
		} else {
			$this->headers[ $name ][] = $values;
		}
	}

	/**
	 * Removes a header.
	 *
	 * @param  string $name The header name.
	 *
	 * @return void
	 */
	public function remove( string $name ): void {
		unset( $this->headers[ strtr( $name, self::UPPER, self::LOWER ) ] );
	}

	/**
	 * Converts the internal headers array into a flat array of header strings.
	 *
	 * @return string[] A flat array of headers in "header-name: value" format.
	 */
	public function raw(): array {
		$result = [];

		foreach ( $this->headers as $name => $values ) {
			foreach ( $values as $value ) {
				$result[] = sprintf( '%s: %s', strtr( $name, self::UPPER, self::LOWER ), $value );
			}
		}

		return $result;
	}

	/**
	 * Normalize and format the HTTP headers.
	 *
	 * @param  string[] $headers The raw HTTP headers.
	 */
	private function normalize_headers( array $headers ): void {
		foreach ( $headers as $header => $content ) {
			// Parse response type headers.
			if ( str_contains( $content, ':' ) ) {
				// Split the header into name and value parts.
				[ $name, $value ] = explode( ':', $content, 2 ) + [ null, null ];

				$name  = strtr( trim( $name ), self::UPPER, self::LOWER );
				$value = trim( $value );
			} else {
				$name  = strtr( $header, self::UPPER, self::LOWER );
				$value = $content;
			}

			$this->set( $name, $value, false );
		}
	}
}
