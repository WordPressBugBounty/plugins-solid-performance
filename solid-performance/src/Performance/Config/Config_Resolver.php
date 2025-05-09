<?php
/**
 * Resolves configuration items from multiple sources.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Config;

/**
 * Resolves configuration items from multiple sources.
 *
 * @package SolidWP\Performance
 */
final class Config_Resolver {

	/**
	 * Merge configuration arrays, with values from the last array overriding values from the
	 * previous arrays.
	 *
	 * @param  array<string, mixed> ...$configs The different config sources.
	 *
	 * @return array
	 */
	public function merge_configs( array ...$configs ): array {
		$merged = [];

		foreach ( $configs as $config ) {
			$merged = $this->replace_recursive( $merged, $config );
		}

		return $merged;
	}

	/**
	 * Recursively merges two arrays, with values from the second array overriding those in the first.
	 *
	 * This method performs a deep merge:
	 * - If both values for a key are arrays, it merges them recursively.
	 * - If one value is an array and the other is a scalar, the scalar overrides the array.
	 * - For numeric keys in indexed arrays, the values from the second array replace those in the first array.
	 *
	 * @param mixed[] $array1 The base array to merge into.
	 * @param mixed[] $array2 The overriding array.
	 *
	 * @return mixed[]
	 */
	private function replace_recursive( array $array1, array $array2 ): array {
		foreach ( $array2 as $key => $value ) {
			if ( is_array( $value ) && array_is_list( $value ) === false ) {
				$array1[ $key ] = $this->replace_recursive( $array1[ $key ] ?? [], $value );
			} else {
				$array1[ $key ] = $value;
			}
		}

		return $array1;
	}
}
