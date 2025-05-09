<?php
/**
 * All functionality related to deactivating the plugin.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Plugin;

use SolidWP\Performance\Cache_Delivery\Nginx\Manager as NginxManager;
use SolidWP\Performance\Config\Advanced_Cache;
use SolidWP\Performance\Container;
use SolidWP\Performance\Cache_Delivery\Htaccess\Manager as HtaccessManager;
use SolidWP\Performance\Cron\Scheduler;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles actions that should run when the plugin is deactivated.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
final class Deactivator {

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * The logger.
	 *
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Container       $container The container.
	 * @param LoggerInterface $logger The logger.
	 */
	private function __construct(
		Container $container,
		LoggerInterface $logger
	) {
		$this->container = $container;
		$this->logger    = $logger;
	}

	/**
	 * Lazy-instantiated callable for register_deactivation_hook.
	 *
	 * @return callable
	 */
	public static function callback(): callable {
		return static function (): void {
			$container = swpsp_plugin()->init()->container();
			$logger    = $container->get( LoggerInterface::class );

			$instance = new self( $container, $logger );

			$instance->deactivate();
		};
	}

	/**
	 * Deactivation hook.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function deactivate(): void {
		$this->remove_advanced_cache();
		$this->remove_htaccess_rules();
		$this->add_nginx_bypass_rules();
		$this->clear_scheduled_tasks();
	}

	/**
	 * Remove the generated advanced-cache.php drop-in.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function remove_advanced_cache(): void {
		$this->container->get( Advanced_Cache::class )->remove();
	}

	/**
	 * Remove our htaccess rules.
	 *
	 * @return void
	 */
	private function remove_htaccess_rules(): void {
		$this->container->get( HtaccessManager::class )->remove_rules();
	}

	/**
	 * Write the "bypass cache rules" to the swpsp-nginx.conf. We don't want to delete
	 * this file as it could bring down their site on the next Nginx reload.
	 *
	 * @return void
	 */
	private function add_nginx_bypass_rules(): void {
		$manager = $this->container->get( NginxManager::class );

		try {
			if ( ! $manager->exists() ) {
				return;
			}

			$manager->bypass();
		} catch ( Throwable $e ) {
			$this->logger->error(
				'Deactivation: error adding Nginx bypass rules',
				[
					'error'     => $e->getMessage(),
					'exception' => $e,
				]
			);
		}
	}

	/**
	 * Clear scheduled tasks.
	 *
	 * @return void
	 */
	private function clear_scheduled_tasks(): void {
		$this->container->get( Scheduler::class )->disable_tasks();
	}
}
