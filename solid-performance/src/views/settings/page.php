<?php
/**
 * Settings HTML Kadence Performance.
 */

do_action( 'stellarwp/telemetry/optin', 'solid-performance' );

?>
<div class="wrap solidwp-performance-settings-page">
	<div class="solidwp-performance-settings-container">
		<div class="solidwp-performance-settings-wrap">
			<?php if ( apply_filters( 'kadence_performance_show_logo', true ) ) : ?>
				<div class="solidwp-performance-settings-header">
					<img
						src="<?php echo esc_url( trailingslashit( plugin_dir_url( SWPSP_PLUGIN_FILE ) ) . 'images/solid_performance_logo.svg' ); ?>"/>
				</div>
			<?php endif ?>
			<!-- Settings Page Header hide other notices -->
			<div class="solidwp-performance-settings-title">
				<h2><?php esc_html_e( 'Performance Settings', 'solid-performance' ); ?></h2>
			</div>
			<div class="solidwp-performance-settings-main">
			</div>
		</div>
	</div>
</div>
