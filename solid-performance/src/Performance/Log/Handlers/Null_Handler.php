<?php
/**
 * Black hole.
 *
 * Any record it can handle will be thrown away.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Log\Handlers;

use SolidWP\Performance\Monolog\Handler\AbstractHandler;

/**
 * Black hole.
 *
 * Any record it can handle will be thrown away.
 *
 * @phpstan-import-type Record from \Monolog\Logger
 */
final class Null_Handler extends AbstractHandler {

	/**
	 * Throw all logs away.
	 *
	 * @param array $record The monolog Record.
	 *
	 * @phpstan-param Record $record
	 *
	 * @return bool
	 */
	public function handle( array $record ): bool {
		return $record['level'] >= $this->level;
	}
}
