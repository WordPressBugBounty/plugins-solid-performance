<?php
/**
 * Provides methods to format a storage key.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Storage\Traits;

use SolidWP\Performance\Storage\Contracts\Storage;
use SolidWP\Performance\Storage\Exceptions\InvalidKeyException;

/**
 * @mixin Storage
 */
trait With_Key_Formatter {

	/**
	 * Converts a storage key into a string.
	 *
	 * @param string|int|float|mixed[]|object $key The storage key. Accepts any variable that can be json encoded.
	 *
	 * @throws InvalidKeyException When the storage key is empty.
	 */
	private function key( $key ): string {
		if ( ! $key ) {
			throw new InvalidKeyException( 'The storage key cannot be empty' );
		}

		return is_scalar( $key ) ? (string) $key : md5( (string) wp_json_encode( $key ) );
	}
}
