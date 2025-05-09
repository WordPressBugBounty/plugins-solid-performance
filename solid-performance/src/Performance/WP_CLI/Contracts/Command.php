<?php
/**
 * The abstract Command class for building solid WP-CLI commands.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\WP_CLI\Contracts;

use WP_CLI_Command;

/**
 * The abstract Command class for building solid WP-CLI commands.
 *
 * @package SolidWP\Performance
 */
abstract class Command extends WP_CLI_Command {
	protected const SUCCESS = 0;
	protected const ERROR   = 1;
}
