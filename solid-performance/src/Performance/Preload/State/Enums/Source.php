<?php
/**
 * The preloader source pseudo-enum.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\State\Enums;

/**
 * The preloader source pseudo-enum.
 *
 * The source is what initiated or canceled the preloader.
 *
 * @package SolidWP\Performance
 */
final class Source {

	/**
	 * If the preloader was started from the web UI.
	 */
	public const WEB = 'web';

	/**
	 * If the preloader was started from the CLI.
	 */
	public const CLI = 'cli';

	/**
	 * Prevent instantiation of pseudo-enum.
	 */
	private function __construct() {
	}

	/**
	 * Determine the source at runtime.
	 *
	 * @return string
	 */
	public static function detect_source(): string {
		return defined( 'WP_CLI' ) && WP_CLI ? self::CLI : self::WEB;
	}

	/**
	 * Validate if the provided source is valid.
	 *
	 * @param  string $source  The source.
	 *
	 * @return bool
	 */
	public static function is_valid( string $source ): bool {
		return in_array(
			$source,
			[
				self::WEB,
				self::CLI,
			],
			true
		);
	}
}
