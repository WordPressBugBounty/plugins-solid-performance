<?php
/**
 * Loads our custom htaccess configuration.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Htaccess;

use RuntimeException;
use SolidWP\Performance\Page_Cache\Cache_Path;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Gzip;
use SolidWP\Performance\Page_Cache\Compression\Strategies\Html;
use SolidWP\Performance\View\Contracts\View;
use SolidWP\Performance\View\Exceptions\FileNotFoundException;

/**
 * Loads our custom htaccess configuration.
 *
 * @package SolidWP\Performance
 */
final class Template_Loader {

	public const VIEW = 'cache/htaccess';

	/**
	 * @var View
	 */
	private View $view;

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @param View       $view The view renderer.
	 * @param Cache_Path $cache_path The cache path object.
	 */
	public function __construct(
		View $view,
		Cache_Path $cache_path
	) {
		$this->view       = $view;
		$this->cache_path = $cache_path;
	}

	/**
	 * Get our htaccess config template, with the dynamic values replaced.
	 *
	 * @throws FileNotFoundException If we can't find the view file.
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return string
	 */
	public function get(): string {
		$page_cache_dir = $this->cache_path->get_page_cache_dir();

		// Get the RewriteBase.
		$home_root = parse_url( home_url() );
		$base      = trailingslashit( $home_root['path'] ?? '/' );

		$root = swpsp_get_document_root();

		// e.g. "wp-content/cache/solid-performance/page".
		$cache_path = str_replace( $root, '', $page_cache_dir );

		$args = [
			'base'             => $base,
			'cache_path'       => $cache_path,
			'site_cache_path'  => $this->cache_path->get_site_cache_dir(),
			'extensions_regex' => sprintf( '%s|%s', Html::EXT, Gzip::EXT ),
		];

		return $this->view->render_to_string( self::VIEW, $args );
	}
}
