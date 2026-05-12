<?php
/**
 * Handles all customizations for the opt-in modal.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Telemetry;

use SolidWP\Performance\Assets\Asset;

/**
 * Handles all customizations for the opt-in modal.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
final class Modal {

	/**
	 * @var Asset
	 */
	private Asset $asset;

	/**
	 * @param  Asset $asset The asset instance.
	 */
	public function __construct( Asset $asset ) {
		$this->asset = $asset;
	}

	/**
	 * Customizes the optin_args.
	 *
	 * @filter stellarwp/telemetry/optin_args
	 *
	 * @since 0.1.0
	 *
	 * @param array  $args          The arguments used to render the modal.
	 * @param string $stellar_slug The stellar slug of the plugin displaying the modal.
	 *
	 * @return array<string,mixed>
	 */
	public function optin_args( array $args, string $stellar_slug ): array {
		if ( $stellar_slug !== 'solid-performance' ) {
			return $args;
		}

		$args['plugin_logo']        = $this->asset->get_url( 'images/solid_performance_logo.svg' );
		$args['plugin_logo_width']  = 300;
		$args['plugin_logo_height'] = 50;
		$args['permissions_url']    = 'https://go.solidwp.com/solid-performance-opt-in-usage-sharing';
		$args['tos_url']            = 'https://go.solidwp.com/solid-performance-terms-usage-modal';
		$args['privacy_url']        = 'https://go.solidwp.com/solid-performance-privacy-usage-modal';
		$args['plugin_logo_alt']    = esc_attr__( 'Kadence Performance Logo', 'solid-performance' );
		$args['plugin_name']        = esc_html__( 'Kadence Performance', 'solid-performance' );

		$args['heading'] = sprintf(
			// Translators: The plugin name.
			esc_html__( 'We hope you love %s.', 'solid-performance' ),
			$args['plugin_name']
		);

		$args['intro'] = $this->get_intro( $args['user_name'] );

		return $args;
	}

	/**
	 * Provides the intro text with the current users's display name inserted.
	 *
	 * @param string $user_name The user to which the modal is shown.
	 *
	 * @return string
	 */
	public function get_intro( string $user_name ): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return esc_html__( 'Want to help shape the future of Kadence Performance? Opting in shares anonymous usage data with our team at Liquid Web, the company behind Kadence Performance, so we can keep building tools that work better for you. You\'ll also receive product updates and important announcements via email—unsubscribe any time. Click \'Allow & Continue\' below to join us.', 'solid-performance' );
	}
}
