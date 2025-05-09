<?php
/**
 * Validate the Image Processor actually works before allowing it to be enabled.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Image_Transformation;

use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Preload\Client;
use SolidWP\Performance\StellarWP\Arrays\Arr;
use Throwable;
use WP_Error;
use WP_Http;
use WP_HTTP_Response;
use WP_REST_Response;

/**
 * Validate the Image Processor actually works before allowing it to be enabled.
 *
 * @package SolidWP\Performance
 */
final class Settings_Validator {

	public const ERROR_INVALID_PROCESSOR = 'swpsp_invalid_image_processor';
	public const ERROR_FAILED_TO_ENABLE  = 'swpsp_failed_to_enable_image_transformation';

	/**
	 * @var Client
	 */
	private Client $client;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @var Processor_Factory
	 */
	private Processor_Factory $factory;

	/**
	 * @param Client            $client The HTTP Client.
	 * @param Config            $config The Config Object.
	 * @param Processor_Factory $factory The Processor Factory.
	 */
	public function __construct(
		Client $client,
		Config $config,
		Processor_Factory $factory
	) {
		$this->client  = $client;
		$this->config  = $config;
		$this->factory = $factory;
	}

	/**
	 * Attempt to access our transformed test image and prevent the settings from being saved if it
	 * fails.
	 *
	 * @filter solidwp/performance/settings/before_save
	 *
	 * @param WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response Result to send to the client. Usually a WP_REST_Response or WP_Error.
	 * @param array                                            $settings The array of settings.
	 *
	 * @return mixed|null
	 */
	public function validate( $response, array $settings ) {
		$image_transformation = Arr::get( $settings, 'page_cache.image_transformation.enabled', false );

		// The user isn't trying to enable this feature.
		if ( ! $image_transformation ) {
			return $response;
		}

		$processor_type = Arr::get( $settings, 'page_cache.image_transformation.processor', '' );

		// This should never happen, but it's possible.
		if ( ! $processor_type ) {
			return $response;
		}

		$currently_enabled      = $this->config->get( 'page_cache.image_transformation.enabled' );
		$current_processor_type = $this->config->get( 'page_cache.image_transformation.processor' );

		// If this is already enabled with the same processor, don't test it again.
		if ( $currently_enabled && ( $processor_type === $current_processor_type ) ) {
			return $response;
		}

		$processor = $this->factory->make( $processor_type );

		if ( ! $processor ) {
			return new WP_Error(
				self::ERROR_INVALID_PROCESSOR,
				/* translators: %s: Image processor type */
				sprintf( __( 'Invalid Image Processor: %s', 'solid-performance' ), $processor_type ),
				[
					'status' => WP_Http::BAD_REQUEST,
				]
			);
		}

		$test_image_url = plugin_dir_url( SWPSP_PLUGIN_FILE ) . 'images/transformation-test.jpeg';

		$transformed_url = $processor->filter_attachment_url(
			$test_image_url,
			[
				'width'  => 25,
				'height' => 25,
			]
		);

		try {
			$code = $this->client->request( $transformed_url )->getStatusCode();

			// If the transformed URL isn't accessible, return an error.
			if ( $code !== 200 && $code !== 304 ) {
				$response = $this->build_error( $transformed_url, $processor_type );
			}
		} catch ( Throwable $e ) {
			$response = $this->build_error( $transformed_url, $processor_type );
		}

		return $response;
	}

	/**
	 * Get the WP Error message.
	 *
	 * @param string $test_url The URL to display in the error message.
	 * @param string $processor_name The name of the processor.
	 *
	 * @return WP_Error
	 */
	private function build_error( string $test_url, string $processor_name ): WP_Error {
		return new WP_Error(
			self::ERROR_FAILED_TO_ENABLE,
			sprintf(
				/* translators: 1: Processor name, 2: URL of the test image */
				__( 'Failed to enable %1$s Image Transformation. Verify your %1$s settings and try again. Ensure this URL is accessible: %2$s.', 'solid-performance' ),
				ucfirst( $processor_name ),
				esc_url( $test_url )
			),
			[
				'status' => WP_Http::BAD_REQUEST,
			]
		);
	}
}
