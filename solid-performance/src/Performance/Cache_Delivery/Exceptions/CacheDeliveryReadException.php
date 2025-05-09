<?php
/**
 * Thrown when we have trouble reading an .htaccess file.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Exceptions;

use RuntimeException;

/**
 * Thrown when we have trouble reading a cache delivery file.
 *
 * @package SolidWP\Performance
 */
final class CacheDeliveryReadException extends RuntimeException {

}
