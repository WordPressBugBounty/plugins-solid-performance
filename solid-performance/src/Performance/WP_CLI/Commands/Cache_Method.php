<?php
/**
 * The wp solid perf cache-method subcommand.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\WP_CLI\Commands;

use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Cache_Delivery\Manager_Collection;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\WP_CLI\Contracts;
use WP_CLI;

/**
 * Get and set the Cache Delivery Method.
 *
 * @package SolidWP\Performance
 */
final class Cache_Method extends Contracts\Command {

	private const FLAG_PORCELAIN = 'porcelain';

	/**
	 * @var Manager_Collection
	 */
	private Manager_Collection $manager_collection;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param Manager_Collection $manager_collection The cache delivery collection.
	 * @param Config             $config The config object.
	 */
	public function __construct(
		Manager_Collection $manager_collection,
		Config $config
	) {
		$this->manager_collection = $manager_collection;
		$this->config             = $config;

		parent::__construct();
	}

	/**
	 * Check which cache method is in use.
	 *
	 * [--porcelain]
	 * : Output the cache delivery method.
	 *
	 * ## EXAMPLES
	 *
	 *      # Check which Cache Delivery Method is in use.
	 *      $ wp solid perf cache-method status
	 *      Page Cache Delivery Method: htaccess.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 * @param mixed[] $assoc_args Command options.
	 *
	 * @return int
	 */
	public function status( array $args, array $assoc_args ): int {
		$porcelain = WP_CLI\Utils\get_flag_value( $assoc_args, self::FLAG_PORCELAIN );

		$method = $this->config->get( 'page_cache.cache_delivery.method' );

		if ( $porcelain ) {
			WP_CLI::line( $method );
		} else {
			WP_CLI::line( sprintf( 'Page Cache Delivery Method: %s.', $method ) );
		}

		return self::SUCCESS;
	}

	/**
	 * Set the cache delivery method.
	 *
	 * Automatically force adds or removes the htaccess rules depending on the method,
	 * be sure your server supports the selected cache delivery method.
	 *
	 * ## OPTIONS
	 *
	 * <method>
	 * : The cache delivery method, e.g. php, htaccess, nginx.
	 * ---
	 * options:
	 *  - php
	 *  - htaccess
	 *  - nginx
	 * ---
	 * ## EXAMPLES
	 *
	 *       # Use PHP for the cache delivery method.
	 *       $ wp solid perf cache-method set php
	 *       Success: Cache Delivery Method Set: php
	 *
	 *       # Use htaccess for the cache delivery method.
	 *       $ wp solid perf cache-method set htaccess
	 *       Success: Cache Delivery Method Set: htaccess
	 *
	 * @param mixed[] $args Positional command line arguments.
	 *
	 * @return int
	 */
	public function set( array $args ): int {
		[ $method ] = $args + [ '' ];

		$method = trim( $method );

		$valid_methods = Cache_Delivery_Type::all();

		if ( ! in_array( $method, $valid_methods, true ) ) {
			WP_CLI::error(
				sprintf(
					'Invalid Cache Delivery Method. Valid options: %s.',
					implode( ', ', $valid_methods )
				)
			);

			return self::ERROR;
		}

		$this->config->set( 'page_cache.cache_delivery.method', $method )->save();

		if ( $method === Cache_Delivery_Type::HTACCESS ) {
			WP_CLI::line( 'Force adding Solid Performance htaccess rules...' );

			$htaccess_manager = $this->manager_collection->get( Cache_Delivery_Type::HTACCESS );

			if ( ! $htaccess_manager->force_add_rules() ) {
				WP_CLI::error( 'Failed to add Solid Performance htaccess rules.' );

				return self::ERROR;
			}
		} elseif ( $method === Cache_Delivery_Type::NGINX ) {
			WP_CLI::line( 'Generating nginx.conf...' );

			$nginx_manager = $this->manager_collection->get( Cache_Delivery_Type::NGINX );

			if ( ! $nginx_manager->add() ) {
				WP_CLI::error( 'Failed to write Solid Performance nginx.conf' );

				return self::ERROR;
			}

			WP_CLI::line( sprintf( 'nginx.conf saved to: %s', $nginx_manager->path() ) );
		}

		WP_CLI::success( sprintf( 'Cache Delivery Method Set: %s.', $method ) );

		return self::SUCCESS;
	}
}
