<?php
/**
 * Uses the wp_options table to store data.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Storage\Drivers;

use Closure;
use SolidWP\Performance\Storage\Contracts\Storage;
use SolidWP\Performance\Storage\Exceptions\InvalidKeyException;
use SolidWP\Performance\Storage\Traits\With_Key_Formatter;

/**
 * Uses the wp_options table to store data.
 *
 * @package SolidWP\Performance
 */
class Option_Storage implements Storage {

	use With_Key_Formatter;

	/**
	 * Put a value in storage.
	 *
	 * @param  string|int|float|mixed[]|object $key     The storage key. Accepts any variable that can be json encoded.
	 * @param  mixed                           $value   The value to store.
	 * @param  int                             $expire  The storage lifespan in seconds.
	 *
	 * @throws InvalidKeyException If passed an invalid storage key.
	 */
	public function set( $key, $value, int $expire = 0 ): bool {
		$data = [
			'value'      => $value,
			'expiration' => $expire > 0 ? time() + $expire : 0,
		];

		return update_option( $this->key( $key ), $data, false );
	}

	/**
	 * Get a value from storage.
	 *
	 * @param  string|int|float|mixed[]|object $key  The storage key. Accepts any variable that can be json encoded.
	 *
	 * @throws InvalidKeyException If passed an invalid storage key.
	 *
	 * @return null|mixed Returns null if we can't find the storage value.
	 */
	public function get( $key ) {
		$data = (array) get_option( $this->key( $key ), [] );

		if ( isset( $data['expiration'] ) && $data['expiration'] > 0 && $data['expiration'] < time() ) {
			$this->delete( $key );

			return null;
		}

		return $data['value'] ?? null;
	}

	/**
	 * Delete a value from storage.
	 *
	 * @param  string|int|float|mixed[]|object $key  The storage key.
	 *
	 * @throws InvalidKeyException If passed an invalid storage key.
	 */
	public function delete( $key ): bool {
		return delete_option( $this->key( $key ) );
	}

	/**
	 * Get an item from storage, or execute the given Closure and store the result.
	 *
	 * @param  string|int|float|mixed[]|object $key       The storage key.
	 * @param  Closure                         $callback  The callback used to generate and store the value.
	 * @param  int                             $expire    The storage lifespan in seconds.
	 *
	 * @throws InvalidKeyException If passed an invalid storage key.
	 *
	 * @return mixed The storage value.
	 */
	public function remember( $key, Closure $callback, int $expire = 0 ) {
		$value = $this->get( $key );

		if ( ! is_null( $value ) ) {
			return $value;
		}

		$value = $callback();

		$this->set( $key, $value, $expire );

		return $value;
	}

	/**
	 * Retrieve an item from storage and delete it.
	 *
	 * @param  string|int|float|mixed[]|object $key  The storage key.
	 *
	 * @throws InvalidKeyException If passed an invalid storage key.
	 */
	public function pull( $key ) {
		$value = $this->get( $key );

		$this->delete( $key );

		return $value;
	}
}
