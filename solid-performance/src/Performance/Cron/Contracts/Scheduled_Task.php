<?php
/**
 * The scheduled task abstract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron\Contracts;

use ReflectionClass;

/**
 * The scheduled task abstract.
 *
 * @package SolidWP\Performance
 */
abstract class Scheduled_Task implements Task {

	/**
	 * @inheritDoc
	 */
	public function hook(): string {
		return sprintf( 'swpsp_%s', strtolower( ( new ReflectionClass( static::class ) )->getShortName() ) );
	}
}
