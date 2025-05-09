<?php
/**
 * Register logging definitions in the container.
 *
 * @package SolidWP\Performance
 */

declare(strict_types=1);

namespace SolidWP\Performance\Log;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Log\Handlers\Null_Handler;
use SolidWP\Performance\Monolog\Formatter\LineFormatter;
use SolidWP\Performance\Monolog\Handler\ErrorLogHandler;
use SolidWP\Performance\Monolog\Logger;
use SolidWP\Performance\Monolog\Processor\PsrLogMessageProcessor;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * Register logging definitions in the container.
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_logging();
		$this->register_log_actions();
	}

	/**
	 * Register our logging system.
	 *
	 * @return void
	 */
	private function register_logging(): void {
		// Enable logging to the error log if WP_DEBUG is enabled and error_log is not listed in the php.ini/fpm disable_functions directive.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && function_exists( 'error_log' ) ) {
			/**
			 * Filter the log level to use when debugging.
			 *
			 * @param string $log_level One of: debug, info, notice, warning, error, critical, alert, emergency
			 */
			$log_level = apply_filters( 'solidwp/performance/log/level', 'debug' );

			$this->container->when( ErrorLogHandler::class )
							->needs( '$level' )
							->give( static fn(): int => Logger::toMonologLevel( $log_level ) );

			$this->container->when( LineFormatter::class )
							->needs( '$dateFormat' )
							->give( static fn(): string => 'd/M/Y:H:i:s O' );

			$this->container->bind(
				LoggerInterface::class,
				function () {
					$logger  = new Logger( 'solid_performance' );
					$handler = $this->container->get( ErrorLogHandler::class );
					$handler->setFormatter( $this->container->get( LineFormatter::class ) );

					$logger->pushHandler( $handler );
					$logger->pushProcessor( new PsrLogMessageProcessor() );

					return $logger;
				}
			);
		} else {
			// Disable logging.
			$this->container->bind(
				LoggerInterface::class,
				static function () {
					$logger = new Logger( 'null' );
					$logger->pushHandler( new Null_Handler() );

					return $logger;
				}
			);
		}
	}

	/**
	 * Register actions so logging can be performed via do_action().
	 *
	 * @return void
	 */
	private function register_log_actions(): void {
		add_action( Log::EMERGENCY, $this->container->callback( LoggerInterface::class, 'emergency' ), 10, 2 );
		add_action( Log::ALERT, $this->container->callback( LoggerInterface::class, 'alert' ), 10, 2 );
		add_action( Log::CRITICAL, $this->container->callback( LoggerInterface::class, 'critical' ), 10, 2 );
		add_action( Log::ERROR, $this->container->callback( LoggerInterface::class, 'error' ), 10, 2 );
		add_action( Log::WARNING, $this->container->callback( LoggerInterface::class, 'warning' ), 10, 2 );
		add_action( Log::NOTICE, $this->container->callback( LoggerInterface::class, 'notice' ), 10, 2 );
		add_action( Log::DEBUG, $this->container->callback( LoggerInterface::class, 'debug' ), 10, 2 );
	}
}
