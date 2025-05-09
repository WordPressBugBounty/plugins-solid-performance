<?php
/**
 * The Settings REST Validator which provides a filter to allow other systems to short-circuit the
 * settings REST API response before the data is saved.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Admin;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The Settings REST Validator which provides a filter to allow other systems to short-circuit the
 * settings REST API response before the data is saved.
 *
 * @package SolidWP\Performance
 */
final class Rest_Validator {

	/**
	 * If our settings are being saved via /wp/v2/settings, allow us to short-circuit the response for more
	 * in-depth validation.
	 *
	 * @filter rest_request_before_callbacks
	 *
	 * @param WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response Result to send to the client.
	 *                                                                    Usually a WP_REST_Response or WP_Error.
	 * @param array                                            $handler Route handler used for the request.
	 * @param WP_REST_Request                                  $request Request used to generate the response.
	 *
	 * @return mixed|null
	 */
	public function validate( $response, array $handler, WP_REST_Request $request ) {
		// If there's already a response something else already processed this.
		if ( $response ) {
			return $response;
		}

		if ( $request->get_route() !== '/wp/v2/settings' || $request->get_method() !== 'POST' ) {
			return $response;
		}

		$params   = $request->get_params();
		$settings = $params[ Settings_Page::SETTINGS_SLUG ] ?? [];

		if ( ! $settings ) {
			return $response;
		}

		/**
		 * Filters the response before executing any REST API callbacks allowing us to short-circuit the response with
		 * our own validation. Return a WP_Error object to short-circuit.
		 *
		 * @param WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response Result to send to the client.
		 *                                                                     Usually a WP_REST_Response or WP_Error.
		 * @param array $settings The settings array.
		 * @param array $handler Route handler used for the request.
		 * @param WP_REST_Request $request Request used to generate the response.
		 *
		 * @return mixed|null
		 */
		return apply_filters( 'solidwp/performance/settings/before_save', $response, $settings, $handler, $request );
	}
}
