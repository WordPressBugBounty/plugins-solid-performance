<?php
/**
 * Thrown when trying to stop a timer that doesn't exist.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Timer\Exceptions;

use LogicException;

/**
 * Thrown when trying to stop a timer that doesn't exist.
 *
 * @package SolidWP\Performance
 */
final class NoActiveTimerException extends LogicException {

}
