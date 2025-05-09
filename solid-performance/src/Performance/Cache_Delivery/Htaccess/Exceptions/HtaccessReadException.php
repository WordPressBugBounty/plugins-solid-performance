<?php
/**
 * Thrown when we have trouble reading an .htaccess file.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess\Exceptions;

use RuntimeException;

/**
 * Thrown when we have trouble reading an .htaccess file.
 *
 * @package SolidWP\Performance
 */
final class HtaccessReadException extends RuntimeException {

}
