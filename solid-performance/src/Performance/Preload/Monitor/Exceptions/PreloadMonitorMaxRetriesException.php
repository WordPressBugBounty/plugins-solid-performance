<?php
/**
 * Thrown when the preload monitor reaches its max retries.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Monitor\Exceptions;

use RuntimeException;

/**
 * Thrown when the preload monitor reaches its max retries.
 *
 * @package SolidWP\Performance
 */
final class PreloadMonitorMaxRetriesException extends RuntimeException {

}
