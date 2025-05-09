<?php
/**
 * The wp solid perf preload subcommand.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\WP_CLI\Commands;

use SolidWP\Performance\Preload\Exceptions\PreloaderInProgressException;
use SolidWP\Performance\Preload\Exceptions\PreloadException;
use SolidWP\Performance\Preload\Monitor\Exceptions\PreloadMonitorMaxRetriesException;
use SolidWP\Performance\Preload\Preload_Mode_Manager;
use SolidWP\Performance\Preload\Preload_Scheduler;
use SolidWP\Performance\Preload\State\Enums\Status;
use SolidWP\Performance\WP_CLI\Contracts;
use SolidWP\Performance\WP_CLI\Progress;
use Throwable;
use WP_CLI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages Solid Performance site-wide preloading.
 *
 * @package SolidWP\Performance
 */
final class Preload extends Contracts\Command {

	private const OPTION_FORCE = 'force';

	/**
	 * @var Preload_Scheduler
	 */
	private Preload_Scheduler $preloader;

	/**
	 * @var Preload_Mode_Manager
	 */
	private Preload_Mode_Manager $preload_mode_manager;

	/**
	 * @param Preload_Scheduler    $preloader The preloader.
	 * @param Preload_Mode_Manager $preload_mode_manager The preload mode manager.
	 */
	public function __construct( Preload_Scheduler $preloader, Preload_Mode_Manager $preload_mode_manager ) {
		$this->preloader            = $preloader;
		$this->preload_mode_manager = $preload_mode_manager;

		parent::__construct();
	}

	/**
	 * Check if the preloader is running or not.
	 *
	 * ## EXAMPLES
	 *
	 *      # Check the preloading status.
	 *      $ wp solid perf preload status
	 *      No preloader currently running.
	 *
	 * @return int
	 */
	public function status(): int {
		if ( $this->preloader->is_running() ) {
			WP_CLI::line(
				WP_CLI::colorize(
					sprintf(
						'%%PA preloader is currently running via "%s" and is %d%% complete.',
						$this->preloader->state()->get()->source,
						$this->preloader->progress()
					)
				)
			);

			return self::SUCCESS;
		}

		WP_CLI::line( WP_CLI::colorize( '%GNo preloader currently running.' ) );

		return self::SUCCESS;
	}

	/**
	 * Start the preloader.
	 *
	 * ## OPTIONS
	 *
	 * [--force]
	 * : Force preload the entire site.
	 *
	 * ## EXAMPLES
	 *
	 *      # Preload uncached pages.
	 *      $ wp solid perf preload start
	 *      Success: Preloading completed in 0 hours, 0 minutes, 8 seconds.
	 *
	 *      # Force preload the entire site.
	 *      $ wp solid perf preload start --force
	 *      Success: Preloading completed in 0 hours, 0 minutes, 34 seconds.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 * @param mixed[] $assoc_arg Command options.
	 *
	 * @return int
	 */
	public function start( array $args, array $assoc_arg ): int {
		$force = (bool) WP_CLI\Utils\get_flag_value( $assoc_arg, self::OPTION_FORCE, false );

		WP_CLI::line( 'Attempting to start the preloader...' );

		try {
			$this->preloader->start( $force );
		} catch ( PreloaderInProgressException $e ) {
			WP_CLI::error(
				WP_CLI::colorize(
					sprintf(
						'%s and is %s complete.' . PHP_EOL . 'Try again later or run: %s to cancel the currently running preloader.',
						$e->getMessage(),
						'%g' . $this->preloader->progress() . '%%%n',
						'%Ywp solid perf preload cancel%n'
					)
				)
			);
		} catch ( Throwable $e ) {
			WP_CLI::warning( sprintf( 'Starting Preloader: %s', $e->getMessage() ) );
		}

		$count = $this->preloader->count();

		if ( $force ) {
			$message = sprintf(
				'Preparing to force preload the entire site: Found %s crawled sitemap URLs.',
				"%c$count%n"
			);
		} else {
			$message = sprintf(
				'Preparing to preload uncached pages: Found %s crawled sitemap URLs.',
				"%c$count%n"
			);
		}

		WP_CLI::line( WP_CLI::colorize( $message ) );

		$progress = new Progress( 'Preloading URLs', $count, 1000 );

		while ( $this->preloader->is_running() ) {
			// Ensure we get updated counts.
			wp_cache_flush_runtime();

			$diff = $count - $this->preloader->count();

			$progress->setProgress( $diff, sprintf( '%d/%d', $diff, $count ) );

			try {
				$this->preloader->is_stalled();
			} catch ( PreloadMonitorMaxRetriesException $e ) {
				$this->preloader->fail();

				WP_CLI::error( sprintf( 'Preloading Failed: %s', $e->getMessage() ) );
			} catch ( PreloadException $e ) {
				WP_CLI::warning( sprintf( 'Attempting to restart the Preloader. An error occurred: %s', $e->getMessage() ) );
			}

			usleep( 500 * 1000 );
		}

		$progress->finish();

		do {
			// Ensure we get the state uncached.
			wp_cache_flush_runtime();

			$state = $this->preloader->state()->get();

			if ( $state->status === Status::COMPLETED ) {
				WP_CLI::success( sprintf( 'Preloading completed in %s.', $state->duration ) );
				break;
			} elseif ( $state->status === Status::CANCELED ) {
				WP_CLI::error( sprintf( 'Preloading canceled via "%s".', $state->source ) );
				break;
			}

			usleep( 250 * 1000 );

		} while ( $state->status === Status::RUNNING );

		return self::SUCCESS;
	}

	/**
	 * Cancel the preloader, if running.
	 *
	 * ## EXAMPLES
	 *
	 *       # Cancel preloading.
	 *       $ wp solid perf preload cancel
	 *       Success: Preloading canceled.
	 *
	 * @return int
	 */
	public function cancel(): int {
		if ( ! $this->preloader->is_running() ) {
			WP_CLI::error( 'No preloader is currently running.' );
		}

		try {
			$this->preloader->cancel();
		} catch ( PreloadException $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		WP_CLI::success( 'Preloading canceled.' );

		return self::SUCCESS;
	}

	/**
	 * Toggle high-performance mode for preloading.
	 *
	 * ## OPTIONS
	 *
	 * <action>
	 * : Action to perform. Accepted values: 'on', 'off'.
	 * ---
	 * options:
	 *  - on
	 *  - off
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *      # Enable high-performance mode.
	 *      $ wp solid perf preload perf-mode on
	 *      Success: High-performance mode enabled.
	 *
	 *      # Disable high-performance mode.
	 *      $ wp solid perf preload perf-mode off
	 *      Success: High-performance mode disabled.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 *
	 * @subcommand perf-mode
	 *
	 * @return int
	 */
	public function perf_mode( array $args ): int {
		[ $action ] = $args;

		if ( ! in_array( $action, [ 'on', 'off' ], true ) ) {
			WP_CLI::error( 'Invalid action. Use "on" or "off".' );

			return self::ERROR;
		}

		if ( $action === 'on' ) {
			$this->preload_mode_manager->enable_high_performance_mode();

			WP_CLI::success( 'High-performance mode enabled.' );

			return self::SUCCESS;
		}

		$this->preload_mode_manager->disable_high_performance_mode();

		WP_CLI::success( 'High-performance mode disabled.' );

		return self::SUCCESS;
	}
}
