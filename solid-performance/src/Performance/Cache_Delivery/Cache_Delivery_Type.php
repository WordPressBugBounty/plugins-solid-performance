<?php
/**
 * The Available Cache Delivery Types.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery;

/**
 * The Available Cache Delivery Types.
 *
 * @package SolidWP\Performance
 */
final class Cache_Delivery_Type {

	public const HTACCESS = 'htaccess';
	public const PHP      = 'php';
	public const NGINX    = 'nginx';

	/**
	 * Return all the cache delivery types.
	 *
	 * @return string[]
	 */
	public static function all(): array {
		return [
			self::HTACCESS,
			self::PHP,
			self::NGINX,
		];
	}
}
