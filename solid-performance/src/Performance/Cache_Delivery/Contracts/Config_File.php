<?php
/**
 * Represents a config file for a particular cache delivery strategy.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Contracts;

use RuntimeException;

/**
 * Represents a config file for a particular cache delivery strategy.
 *
 * @package SolidWP\Performance
 */
abstract class Config_File {

	/**
	 * Memoization cache for the file path.
	 *
	 * @var string|null
	 */
	protected ?string $filepath = null;

	/**
	 * Get the server path to the cache delivery strategy config file.
	 *
	 * @throws RuntimeException If something goes seriously wrong finding the path.
	 *
	 * @return string
	 */
	abstract public function get_file_path(): string;

	/**
	 * Check if the cache delivery file exists.
	 *
	 * @throws RuntimeException If something goes seriously wrong finding the path.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		return file_exists( $this->get_file_path() );
	}

	/**
	 * Check if the cache delivery file exists and is writable.
	 *
	 * @throws RuntimeException If something goes seriously wrong finding the path.
	 *
	 * @return bool
	 */
	public function is_writable(): bool {
		return is_writable( $this->get_file_path() );
	}
}
