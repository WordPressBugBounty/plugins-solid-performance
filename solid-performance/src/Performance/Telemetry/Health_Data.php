<?php
/**
 * Handles adding a new section to the site health data screen.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Telemetry;

use RuntimeException;
use SolidWP\Performance\Cache_Delivery\Htaccess\Manager;
use SolidWP\Performance\Page_Cache\Compression\Collection;
use SolidWP\Performance\Page_Cache\Compression\Contracts\Compressible;
use SolidWP\Performance\Preload\Limiter\Core_Counter;
use SolidWP\Performance\Preload\Limiter\Load\System_Load_Monitor;
use SolidWP\Performance\Preload\Preload_Mode_Manager;

/**
 * Handles adding a new section to the site health data screen.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
class Health_Data {

	/**
	 * The collection of compression strategies.
	 *
	 * @var Collection
	 */
	private Collection $compressors;

	/**
	 * @var System_Load_Monitor
	 */
	private System_Load_Monitor $system_load_monitor;

	/**
	 * @var Core_Counter
	 */
	private Core_Counter $core_counter;

	/**
	 * @var Preload_Mode_Manager
	 */
	private Preload_Mode_Manager $preload_mode_manager;

	/**
	 * @var Manager
	 */
	private Manager $htaccess;

	/**
	 * @param Collection           $compressors The collection of compression strategies.
	 * @param System_Load_Monitor  $system_load_monitor The system load monitor.
	 * @param Core_Counter         $core_counter The core counter.
	 * @param Preload_Mode_Manager $preload_mode_manager The preload mode manager.
	 * @param Manager              $htaccess The htaccess manager.
	 */
	public function __construct(
		Collection $compressors,
		System_Load_Monitor $system_load_monitor,
		Core_Counter $core_counter,
		Preload_Mode_Manager $preload_mode_manager,
		Manager $htaccess
	) {
		$this->compressors          = $compressors;
		$this->system_load_monitor  = $system_load_monitor;
		$this->core_counter         = $core_counter;
		$this->preload_mode_manager = $preload_mode_manager;
		$this->htaccess             = $htaccess;
	}

	/**
	 * Adds a new Solid Performance section to site health data.
	 *
	 * @since 0.1.0
	 *
	 * @filter debug_information
	 *
	 * @param array $info The array of site health data.
	 *
	 * @return array
	 */
	public function add_summary_to_telemetry( array $info ): array {
		$page_cache          = swpsp_config_get( 'page_cache' );
		$page_cache_status   = $page_cache['enabled'] ? esc_html__( 'Enabled', 'solid-performance' ) : esc_html__( 'Disabled', 'solid-performance' );
		$cache_dir_writeable = swpsp_direct_filesystem()->is_writable( $page_cache['cache_dir'] ) ? esc_html__( 'Writable', 'solid-performance' ) : esc_html__( 'Not Writable', 'solid-performance' );
		$debug_mode_status   = $page_cache['debug'] ? esc_html__( 'Enabled', 'solid-performance' ) : esc_html__( 'Disabled', 'solid-performance' );
		$exclusion_count     = count( $page_cache['exclusions'] );
		$enabled_compressors = implode(
			', ',
			array_filter(
				array_map( static fn( Compressible $c ): string => $c->encoding(), $this->compressors->enabled() )
			)
		);

		try {
			$system_load       = [];
			$system_load_debug = [];
			$load_averages     = $this->system_load_monitor->load()->all();

			foreach ( $load_averages as $label => $load_average ) {
				$new_label = sprintf(
					/* translators: %s: The system load time, e.g. 1, 5, or 15. */
					_n(
						'%s min',
						'%s mins',
						$label,
						'solid-performance'
					),
					$label
				);

				$formatted_average = number_format( $load_average, 2 );

				$system_load[ $new_label ]   = $formatted_average;
				$system_load_debug[ $label ] = $formatted_average;
			}
		} catch ( RuntimeException $e ) {
			$system_load       = $e->getMessage();
			$system_load_debug = $e->getMessage();
		}

		$core_count         = $this->core_counter->core_count();
		$has_htaccess_rules = $this->htaccess->has_rules();

		$info['solid-performance'] = [
			'label'  => esc_html__( 'Solid Performance', 'solid-performance' ),
			'fields' => [
				'page_cache_status'              => [
					'label' => esc_html__( 'Page cache', 'solid-performance' ),
					'value' => $page_cache_status,
					'debug' => strtolower( $page_cache_status ),
				],
				'cache_directory'                => [
					'label' => esc_html__( 'Cache directory', 'solid-performance' ),
					'value' => $page_cache['cache_dir'],
					'debug' => $page_cache['cache_dir'],
				],
				'cache_directory_writable'       => [
					'label' => esc_html__( 'Cache directory permissions', 'solid-performance' ),
					'value' => $cache_dir_writeable,
					'debug' => strtolower( $cache_dir_writeable ),
				],
				'debug_mode'                     => [
					'label' => esc_html__( 'Debug mode', 'solid-performance' ),
					'value' => $debug_mode_status,
					'debug' => strtolower( $debug_mode_status ),
				],
				'exclusion_count'                => [
					'label' => esc_html__( 'Number of custom exclusions', 'solid-performance' ),
					'value' => $exclusion_count,
				],
				'compression'                    => [
					'label' => esc_html__( 'Supported compression algorithms', 'solid-performance' ),
					'value' => $enabled_compressors,
					'debug' => $enabled_compressors,
				],
				'system_load'                    => [
					'label' => esc_html__( 'System load average', 'solid-performance' ),
					'value' => $system_load,
					'debug' => $system_load_debug,
				],
				'core_count'                     => [
					'label' => esc_html__( 'System CPU core count', 'solid-performance' ),
					'value' => $core_count,
				],
				'high_performance_mode'          => [
					'label' => esc_html__( 'High performance mode', 'solid-performance' ),
					'value' => $this->preload_mode_manager->is_high_performance_mode() ? esc_html__( 'Enabled', 'solid-performance' ) : esc_html__( 'Disabled', 'solid-performance' ),
					'debug' => $this->preload_mode_manager->is_high_performance_mode(),
				],
				'page_cache_delivery_method'     => [
					'label' => esc_html__( 'Page cache delivery method', 'solid-performance' ),
					'value' => swpsp_config_get( 'page_cache.cache_delivery.method' ),
					'debug' => swpsp_config_get( 'page_cache.cache_delivery.method' ),
				],
				'apache_htaccess_rules'          => [
					'label' => esc_html__( 'Apache htaccess rules', 'solid-performance' ),
					'value' => $has_htaccess_rules ? esc_html__( 'Found', 'solid-performance' ) : esc_html__( 'Missing', 'solid-performance' ),
					'debug' => $has_htaccess_rules,
				],
				'image_transformation'           => [
					'label' => esc_html__( 'Image Transformation', 'solid-performance' ),
					'value' => swpsp_config_get( 'page_cache.image_transformation.enabled' ) ? esc_html__( 'Enabled', 'solid-performance' ) : esc_html__( 'Disabled', 'solid-performance' ),
					'debug' => (bool) swpsp_config_get( 'page_cache.image_transformation.enabled' ),
				],
				'image_transformation_processor' => [
					'label' => esc_html__( 'Image Transformation Processor', 'solid-performance' ),
					'value' => ucfirst( (string) swpsp_config_get( 'page_cache.image_transformation.processor' ) ),
					'debug' => swpsp_config_get( 'page_cache.image_transformation.processor' ),
				],
			],
		];

		return $info;
	}
}
