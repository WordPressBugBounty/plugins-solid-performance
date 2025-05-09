<?php
/**
 * Thrown when an invalid storage key is used.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Storage\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when an invalid storage key is used.
 *
 * @package SolidWP\Performance
 */
final class InvalidKeyException extends InvalidArgumentException {

}
