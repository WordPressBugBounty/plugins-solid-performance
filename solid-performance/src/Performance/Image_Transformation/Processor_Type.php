<?php
/**
 * The Available Image Transformation Processors.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Image_Transformation\Contracts\Processor;
use SolidWP\Performance\Image_Transformation\Processors\Bypass;
use SolidWP\Performance\Image_Transformation\Processors\Cloudflare;

/**
 * The Available Image Transformation Processors.
 *
 * @package SolidWP\Performance
 */
final class Processor_Type {

	public const BYPASS     = 'bypass';
	public const CLOUDFLARE = 'cloudflare';

	/**
	 * The map of processor names to their class string.
	 *
	 * @var array<string, class-string>
	 */
	private static array $processors = [
		self::BYPASS     => Bypass::class,
		self::CLOUDFLARE => Cloudflare::class,
	];

	/**
	 * Get the class name associated with a processor.
	 *
	 * @param string $name The processor name.
	 *
	 * @return class-string<Processor>|null
	 */
	public static function tryFrom( string $name ): ?string {
		return self::$processors[ $name ] ?? null;
	}

	/**
	 * Get all the processor names.
	 *
	 * @return string[]
	 */
	public static function names(): array {
		return [
			self::BYPASS,
			self::CLOUDFLARE,
		];
	}
}
