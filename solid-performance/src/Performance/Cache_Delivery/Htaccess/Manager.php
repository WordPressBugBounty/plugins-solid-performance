<?php
/**
 * The htaccess manager.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Cache_Delivery\Contracts\Writable;
use SolidWP\Performance\Cache_Delivery\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\View\Exceptions\FileNotFoundException;

/**
 * The htaccess manager.
 *
 * @package SolidWP\Performance
 */
final class Manager {

	/**
	 * @var Readable
	 */
	private Readable $reader;

	/**
	 * @var Writable
	 */
	private Writable $writer;

	/**
	 * @var Modifier
	 */
	private Modifier $modifier;

	/**
	 * @var Template_Loader
	 */
	private Template_Loader $template_loader;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Readable        $reader The htaccess reader.
	 * @param Writable        $writer The htaccess writer.
	 * @param Modifier        $modifier The htaccess modifier.
	 * @param Template_Loader $template_loader The htaccess template loader.
	 * @param Config          $config The config object.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct(
		Readable $reader,
		Writable $writer,
		Modifier $modifier,
		Template_Loader $template_loader,
		Config $config,
		LoggerInterface $logger
	) {
		$this->reader          = $reader;
		$this->writer          = $writer;
		$this->modifier        = $modifier;
		$this->template_loader = $template_loader;
		$this->config          = $config;
		$this->logger          = $logger;
	}

	/**
	 * Add our rules to the .htaccess file.
	 *
	 * @param bool $update Whether to force update the rules.
	 *
	 * @return bool
	 */
	public function add_rules( bool $update = false ): bool {
		if ( ! $this->supported() ) {
			$this->logger->debug( 'Skipping adding htaccess rules: Apache not detected' );

			return false;
		}

		if ( ! $this->enabled() ) {
			$this->logger->debug( 'Skipping adding htaccess rules: disabled via config' );

			return false;
		}

		/**
		 * Disable adding Solid Performance .htaccess rules.
		 *
		 * @param bool $disabled True to disable.
		 */
		if ( apply_filters( 'solidwp/performance/htaccess/disabled', false ) ) {
			$this->logger->debug( 'Skipping adding htaccess rules: Filtered by "solidwp/performance/htaccess/disabled"' );

			return false;
		}

		return $this->write_rules( $update );
	}

	/**
	 * Ignores any config or filters and force writes the .htaccess file.
	 *
	 * @return bool
	 */
	public function force_add_rules(): bool {
		return $this->write_rules( true );
	}

	/**
	 * Write the htaccess rules.
	 *
	 * @param bool $update Whether to force update the rules.
	 *
	 * @return bool
	 */
	private function write_rules( bool $update = false ): bool {
		try {
			$existing = $this->reader->read();
			$rules    = $this->template_loader->get();

			if ( $update ) {
				$modified = $this->modifier->force_prepend( $existing, $rules );
			} else {
				$modified = $this->modifier->prepend( $existing, $rules );
			}

			if ( $existing === $modified ) {
				$this->logger->info( 'htaccess rules already match; skipping writing' );

				return true;
			}

			return $this->writer->write( $modified );
		} catch ( CacheDeliveryReadException | FileNotFoundException $e ) {
			$this->logger->error(
				'Error adding htaccess rules',
				[
					'exception' => $e,
				]
			);

			return false;
		}
	}

	/**
	 * Remove our rules from the .htaccess file.
	 *
	 * @return bool
	 */
	public function remove_rules(): bool {
		try {
			$existing = $this->reader->read();
			$removed  = $this->modifier->remove( $existing );

			// Our rules have already been removed.
			if ( $removed === $existing ) {
				return true;
			}

			return $this->writer->write( $removed );
		} catch ( CacheDeliveryReadException $e ) {
			$this->logger->error(
				'Error removing htaccess rules',
				[
					'exception' => $e,
				]
			);

			return false;
		}
	}

	/**
	 * Check if our htaccess rules are in place.
	 *
	 * @return bool
	 */
	public function has_rules(): bool {
		try {
			$existing = $this->reader->read();

			return $this->modifier->has_rules( $existing );
		} catch ( CacheDeliveryReadException $e ) {
			return false;
		}
	}

	/**
	 * If this feature is supported by the current server environment.
	 *
	 * @return bool
	 */
	public function supported(): bool {
		// Allow Solid Security define override.
		if ( defined( 'ITSEC_SERVER_OVERRIDE' ) ) {
			return ITSEC_SERVER_OVERRIDE === 'apache';
		}

		global $is_apache;

		return (bool) $is_apache;
	}

	/**
	 * Whether this feature is enabled.
	 *
	 * @return bool
	 */
	public function enabled(): bool {
		return $this->config->get( 'page_cache.cache_delivery.method' ) === Cache_Delivery_Type::HTACCESS;
	}
}
