<?php
/**
 * The provider for all WP_CLI functionality.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

namespace SolidWP\Performance\WP_CLI;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\WP_CLI\Commands\Cache_Method;
use SolidWP\Performance\WP_CLI\Commands\Image_Transformation;
use SolidWP\Performance\WP_CLI\Commands\Performance;
use SolidWP\Performance\WP_CLI\Commands\Preload;
use SolidWP\Performance\WP_CLI\Contracts\Command;
use WP_CLI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The provider for all WP_CLI functionality.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
class Provider extends Service_Provider {

	public const COMMANDS = 'solid_performance.wp_cli.commands';

	/**
	 * {@inheritdoc}
	 */
	public function register(): void {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! class_exists( 'WP_CLI_Command' ) ) {
			return;
		}

		$this->register_subcommands();
		$this->register_commands();
	}

	/**
	 * Register our WP-CLI commands and their definitions in the container.
	 *
	 * Add your command classes to the array.
	 *
	 * @return void
	 */
	private function register_subcommands(): void {
		$this->container->setVar(
			self::COMMANDS,
			[
				'perf'                      => Performance::class,
				'perf preload'              => Preload::class,
				'perf cache-method'         => Cache_Method::class,
				'perf image-transformation' => Image_Transformation::class,
			]
		);
	}

	/**
	 * Register wp solid perf WP-CLI commands.
	 *
	 * @return void
	 */
	private function register_commands(): void {
		/**
		 * @var string $name The command name
		 * @var class-string<Command> $subcommand The subcommand class.
		 */
		foreach ( $this->container->get( self::COMMANDS ) as $name => $subcommand ) {
			WP_CLI::add_command( "solid $name", $this->container->get( $subcommand ) );
		}
	}
}
