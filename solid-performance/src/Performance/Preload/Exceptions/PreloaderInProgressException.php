<?php
/**
 * Thrown when trying to start a preloader and there is one already running.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Exceptions;

use RuntimeException;

/**
 * Thrown when trying to start a preloader and there is one already running.
 *
 * @package SolidWP\Performance
 */
final class PreloaderInProgressException extends RuntimeException {

}
