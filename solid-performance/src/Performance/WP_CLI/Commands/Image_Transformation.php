<?php
/**
 * The `wp solid perf image-transformation` subcommand.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\WP_CLI\Commands;

use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Image_Transformation\Processor_Type;
use SolidWP\Performance\WP_CLI\Contracts;
use WP_CLI;

/**
 * Configure the Image Transformation system.
 *
 * @package SolidWP\Performance
 */
final class Image_Transformation extends Contracts\Command {

	private const FLAG_PORCELAIN = 'porcelain';

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param Config $config The config object.
	 */
	public function __construct( Config $config ) {
		$this->config = $config;

		parent::__construct();
	}

	/**
	 * Check if is enabled and which image transformer processor is in use.
	 *
	 * [--porcelain]
	 * : Output the current image processor.
	 *
	 * ## EXAMPLES
	 *
	 *      # Check if is enabled and which image transformer processor is in use.
	 *      $ wp solid perf image-transformation status
	 *      Enabled: Yes. Image Processor: cloudflare.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 * @param mixed[] $assoc_args Command options.
	 *
	 * @return int
	 */
	public function status( array $args, array $assoc_args ): int {
		$porcelain = WP_CLI\Utils\get_flag_value( $assoc_args, self::FLAG_PORCELAIN );

		$enabled = $this->config->get( 'page_cache.image_transformation.enabled' );
		$method  = $this->config->get( 'page_cache.image_transformation.processor' );

		if ( $porcelain ) {
			WP_CLI::line( $method );
		} else {
			WP_CLI::line(
				sprintf(
					'Enabled: %s.',
					$enabled ? 'Yes' : 'No',
				)
			);

			WP_CLI::line(
				sprintf(
					'Image Processor: %s.',
					$method
				)
			);
		}

		return self::SUCCESS;
	}

	/**
	 * Enable image transformation.
	 *
	 * ## EXAMPLES
	 *
	 *      # Enable image transformation.
	 *      $ wp solid perf image-transformation enable
	 *      Image transformation enabled.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 * @param mixed[] $assoc_args Command options.
	 *
	 * @return int
	 */
	public function enable( array $args, array $assoc_args ): int {
		$this->config->set( 'page_cache.image_transformation.enabled', true )->save();

		WP_CLI::success( 'Image transformation enabled.' );

		return self::SUCCESS;
	}

	/**
	 * Disable image transformation.
	 *
	 * ## EXAMPLES
	 *
	 *      # Disable image transformation.
	 *      $ wp solid perf image-transformation disable
	 *      Image transformation disabled.
	 *
	 * @param mixed[] $args Positional command line arguments.
	 * @param mixed[] $assoc_args Command options.
	 *
	 * @return int
	 */
	public function disable( array $args, array $assoc_args ): int {
		$this->config->set( 'page_cache.image_transformation.enabled', false )->save();

		WP_CLI::success( 'Image transformation disabled.' );

		return self::SUCCESS;
	}

	/**
	 * Set the image transformer processor.
	 *
	 * ## OPTIONS
	 *
	 * <processor>
	 * : The image processor, e.g. cloudflare, bypass
	 * ---
	 * options:
	 *  - cloudflare
	 *  - bypass
	 * ---
	 * ## EXAMPLES
	 *
	 *       # Use Cloudflare as the image processor.
	 *       $ wp solid perf image-transformation set cloudflare
	 *       Success: Image Processor Set: cloudflare
	 *
	 *       # Bypass image processing.
	 *       $ wp solid perf image-transformation set bypass
	 *       Success: Image Processor Set: bypass
	 *
	 * @param mixed[] $args Positional command line arguments.
	 *
	 * @return int
	 */
	public function set( array $args ): int {
		[ $method ] = $args + [ '' ];

		$method = trim( $method );

		$valid_methods = Processor_Type::names();

		if ( ! in_array( $method, $valid_methods, true ) ) {
			WP_CLI::error(
				sprintf(
					'Invalid Image Processor. Valid options: %s.',
					implode( ', ', $valid_methods )
				)
			);

			return self::ERROR;
		}

		$this->config->set( 'page_cache.image_transformation.processor', $method )->save();

		WP_CLI::success( sprintf( 'Image Processor Set: %s.', $method ) );

		return self::SUCCESS;
	}
}
