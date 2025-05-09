<?php
/**
 * Exception thrown when a filesystem operation fails.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Filesystem\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a filesystem operation fails.
 *
 * @package SolidWP\Performance
 */
final class IOException extends RuntimeException {

	/**
	 * The file path.
	 *
	 * @var string|null
	 */
	private ?string $path;

	/**
	 * @param string         $message The exception message.
	 * @param int            $code The error code.
	 * @param Throwable|null $previous The previous exception interface.
	 * @param string|null    $path The file path.
	 */
	public function __construct( string $message, int $code = 0, ?Throwable $previous = null, ?string $path = null ) {
		$this->path = $path;

		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Get the file path associated with exception.
	 *
	 * @return string|null
	 */
	public function get_path(): ?string {
		return $this->path;
	}
}
