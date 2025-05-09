<?php
/**
 * Handles all functionality related to the Settings Page.
 *
 * @since 0.1.1
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Admin;

use SolidWP\Performance\Assets\Asset;
use SolidWP\Performance\Cache_Delivery\Htaccess\Manager;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Config\Default_Config;
use SolidWP\Performance\Cache_Delivery\Cache_Delivery_Type;
use SolidWP\Performance\Image_Transformation\Processor_Type;
use SolidWP\Performance\View\Contracts\View;

/**
 * Handles all functionality related to the settings page.
 *
 * @since 0.1.1
 *
 * @package SolidWP\Performance
 */
class Settings_Page {

	public const MENU_SLUG     = 'swpsp-settings';
	public const SETTINGS_SLUG = 'solid_performance_settings';
	public const VIEW          = 'settings/page';

	/**
	 * @var View
	 */
	private View $view;

	/**
	 * @var Asset
	 */
	private Asset $asset;

	/**
	 * @var Default_Config
	 */
	private Default_Config $default_config;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @var Manager
	 */
	private Manager $htaccess;

	/**
	 * @param View           $view The view renderer.
	 * @param Asset          $asset The asset helper.
	 * @param Default_Config $default_config The default config items.
	 * @param Config         $config The config object.
	 * @param Manager        $htaccess The htaccess manager.
	 */
	public function __construct(
		View $view,
		Asset $asset,
		Default_Config $default_config,
		Config $config,
		Manager $htaccess
	) {
		$this->view           = $view;
		$this->asset          = $asset;
		$this->default_config = $default_config;
		$this->config         = $config;
		$this->htaccess       = $htaccess;
	}

	/**
	 * Adds a new menu item as a settings submenu.
	 *
	 * @since 0.1.1
	 *
	 * @action admin_menu
	 *
	 * @return void
	 */
	public function add_settings_page(): void {
		$page = add_options_page(
			__( 'Solid Performance', 'solid-performance' ),
			__( 'Solid Performance', 'solid-performance' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'build_settings_page' ],
		);
		add_action( "load-{$page}", [ $this, 'refresh_config' ], 1 );
		add_action( "load-{$page}", [ $this, 'load_page_scripts' ] );
	}

	/**
	 * Ensure the default settings data exists.
	 *
	 * @action load-settings_page_swpsp-settings
	 *
	 * @return void
	 */
	public function refresh_config(): void {
		$this->config->refresh()->save();
	}

	/**
	 * Trigger Loading Page Scripts
	 *
	 * @action load-settings_page_swpsp-settings
	 */
	public function load_page_scripts(): void {
		// Do admin head action for this page.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_head' ] );
	}

	/**
	 * Register settings
	 */
	public function register_settings(): void {
		register_setting(
			self::SETTINGS_SLUG,
			self::SETTINGS_SLUG,
			[
				'type'              => 'object',
				'description'       => esc_html__( 'Solid Performance Settings', 'solid-performance' ),
				'sanitize_callback' => [ $this, 'sanitize_setting' ],
				'default'           => [],
				'show_in_rest'      => [
					'schema' => [
						'properties' => [
							'page_cache' => [
								'type'       => 'object',
								'properties' => [
									'cache_dir'            => [
										'type'        => 'string',
										'description' => esc_html__( 'The directory where cache files are stored.', 'solid-performance' ),
									],
									'enabled'              => [
										'type'        => 'boolean',
										'description' => esc_html__( 'The current status of the page cache.', 'solid-performance' ),
									],
									'debug'                => [
										'type'        => 'boolean',
										'description' => esc_html__( 'The current status of debug mode.', 'solid-performance' ),
									],
									'expiration'           => [
										'type'        => 'integer',
										'description' => esc_html__( 'The number of seconds caches should exist before regenerating.', 'solid-performance' ),
									],
									'exclusions'           => [
										'type'        => 'array',
										'description' => esc_html__( 'The rules to use in determining which urls should not be cached.', 'solid-performance' ),
										'items'       => [
											'type' => 'string',
										],
									],
									'compression'          => [
										'type'       => 'object',
										'properties' => [
											'enabled' => [
												'type' => 'boolean',
												'description' => esc_html__( 'Whether storing and serving compressed cache files is enabled.', 'solid-performance' ),
											],
										],
									],
									'preload'              => [
										'type'       => 'object',
										'properties' => [
											'high_performance_mode' => [
												'type' => 'boolean',
												'description' => esc_html__( 'Preload with the maximum batch size. Automatically disabled if we encounter errors.', 'solid-performance' ),
											],
										],
									],
									'lazy_loading'         => [
										'type'       => 'object',
										'properties' => [
											'enabled' => [
												'type' => 'boolean',
												'description' => esc_html__( 'Whether automatic lazy loading is enabled.', 'solid-performance' ),
											],
										],
									],
									'cache_delivery'       => [
										'type'       => 'object',
										'properties' => [
											'method' => [
												'type' => 'string',
												'description' => esc_html__( 'The cache delivery method in use.', 'solid-performance' ),
												'enum' => Cache_Delivery_Type::all(),
											],
										],
									],
									'image_transformation' => [
										'type'       => 'object',
										'properties' => [
											'enabled'   => [
												'type' => 'boolean',
												'description' => esc_html__( 'Whether image transformation is enabled.', 'solid-performance' ),
											],
											'processor' => [
												'type' => 'string',
												'description' => esc_html__( 'The image transformation processor to use.', 'solid-performance' ),
												'enum' => Processor_Type::names(),
											],
										],
									],
								],
							],
						],
					],
				],
			]
		);
	}

	/**
	 * Add settings link
	 *
	 * @param array $links plugin activate/deactivate links array.
	 */
	public function settings_link( array $links ): array {
		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . self::MENU_SLUG ) ) . '">' . __( 'Settings', 'solid-performance' ) . '</a>';
		$help_link     = '<a href="https://go.solidwp.com/performance-help">' . __( 'Help', 'solid-performance' ) . '</a>';

		array_unshift( $links, $settings_link );

		$links[] = $help_link;

		return $links;
	}

	/**
	 * Load Page Scripts
	 */
	public function admin_head(): void {
		$script_meta = $this->asset->get_meta( 'build/settings' );

		// Enqueue the settings page styles and scripts.
		wp_enqueue_style( 'solid-performance-settings', $this->asset->get_url( 'build/settings.css' ), [ 'wp-components' ], $script_meta['version'] );
		wp_enqueue_script( 'solid-performance-settings', $this->asset->get_url( 'build/settings.js' ), $script_meta['dependencies'], $script_meta['version'], true );
		wp_localize_script(
			'solid-performance-settings',
			'swspParams',
			[
				'settings'      => get_option( self::SETTINGS_SLUG, $this->default_config->get() ),
				'cacheDelivery' => [
					'htaccess' => [
						'supported' => $this->htaccess->supported(),
					],
				],
			]
		);
	}

	/**
	 * Outputs the settings page.
	 *
	 * @since 0.1.1
	 *
	 * @return void
	 */
	public function build_settings_page(): void {
		$this->view->render( self::VIEW );
	}

	/**
	 * Sanitize the values provided in the setting.
	 *
	 * @param array $value   The solid_performance_settings value from the request.
	 *
	 * @return array
	 */
	public function sanitize_setting( $value ) {
		$parsed_args = wp_parse_args( $value['page_cache'], $this->default_config->get( 'page_cache' ) );

		if ( isset( $parsed_args['exclusions'] ) ) {
			$exclusions = array_filter(
				$parsed_args['exclusions'],
				function ( $exclusion ) {
					return $exclusion !== '';
				}
			);

			$parsed_args['exclusions'] = array_values( $exclusions );
		}

		$sanitized_value['page_cache'] = $parsed_args;

		return $sanitized_value;
	}
}
