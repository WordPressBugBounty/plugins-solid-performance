<?php
/**
 * Thrown when passing an invalid timer name.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when passing an invalid timer name.
 *
 * @package SolidWP\Performance
 */
final class InvalidTimerNameException extends InvalidArgumentException {

}
