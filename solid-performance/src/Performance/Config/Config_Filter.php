<?php
/**
 * Removes keys from the config array that do not match the keys from the default config.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Config;

/**
 * Removes keys from the config array that do not match the keys from the default config.
 *
 * @package SolidWP\Performance
 */
final class Config_Filter {

	/**
	 * @var Default_Config
	 */
	private Default_Config $default_config;

	/**
	 * @param Default_Config $default_config The default config object.
	 */
	public function __construct( Default_Config $default_config ) {
		$this->default_config = $default_config;
	}

	/**
	 * Remove any config items that do not have a corresponding key from the defaults.
	 *
	 * @param array<string, mixed> $config The config items.
	 * @param array<string, mixed> $defaults The default config items, or a nested slice of them.
	 *
	 * @return array<string, mixed> The filtered config items.
	 */
	public function filter( array $config, array $defaults = [] ): array {
		$filtered = [];
		$defaults = $defaults ?: $this->default_config->get();

		foreach ( $defaults as $key => $value ) {
			if ( ! array_key_exists( $key, $config ) ) {
				continue;
			}

			// If the value isn't a non-empty array, recurse and filter that.
			if ( is_array( $value ) && ! empty( $value ) ) {
				$filtered[ $key ] = $this->filter( $config[ $key ], $value );
			} else {
				// Otherwise, directly add the value from the input.
				$filtered[ $key ] = $config[ $key ];
			}
		}

		return $filtered;
	}
}
