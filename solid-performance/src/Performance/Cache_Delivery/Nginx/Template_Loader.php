<?php
/**
 * Loads a partial nginx.conf with replaced dynamic variables.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use RuntimeException;
use SolidWP\Performance\Page_Cache\Cache_Path;
use SolidWP\Performance\Page_Cache\Expiration;
use SolidWP\Performance\View\Contracts\View;
use SolidWP\Performance\View\Exceptions\FileNotFoundException;

/**
 * Loads a partial nginx.conf with replaced dynamic variables.
 *
 * @package SolidWP\Performance
 */
final class Template_Loader {

	public const VIEW = 'cache/nginx';

	/**
	 * @var View
	 */
	private View $view;

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @var Expiration
	 */
	private Expiration $expiration;

	/**
	 * @param View       $view The view renderer.
	 * @param Cache_Path $cache_path The cache path object.
	 * @param Expiration $expiration The expiration object.
	 */
	public function __construct(
		View $view,
		Cache_Path $cache_path,
		Expiration $expiration
	) {
		$this->view       = $view;
		$this->cache_path = $cache_path;
		$this->expiration = $expiration;
	}

	/**
	 * Get our nginx config template, with the dynamic values replaced.
	 *
	 * @throws FileNotFoundException If we can't find the view file.
	 * @throws RuntimeException If we can't find the document root.
	 *
	 * @return string
	 */
	public function get(): string {
		$page_cache_dir = $this->cache_path->get_page_cache_dir();

		$root = swpsp_get_document_root();

		// e.g. "wp-content/cache/solid-performance/page".
		$cache_path = str_replace( $root, '', $page_cache_dir );

		$args = [
			'cache_path' => $cache_path,
			'expiration' => $this->expiration->get_expiration_length(),
		];

		return $this->view->render_to_string( self::VIEW, $args );
	}
}
