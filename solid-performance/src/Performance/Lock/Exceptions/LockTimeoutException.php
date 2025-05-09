<?php
/**
 * Thrown when a blocking lock is unable to acquire its
 * lock within the timeout.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Lock\Exceptions;

use RuntimeException;

/**
 * Thrown when a blocking lock is unable to acquire its
 * lock within the timeout.
 *
 * @package SolidWP\Performance
 */
final class LockTimeoutException extends RuntimeException {

}
