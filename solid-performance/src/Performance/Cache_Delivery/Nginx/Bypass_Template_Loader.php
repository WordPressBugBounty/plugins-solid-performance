<?php
/**
 * Loads an nginx.conf that bypasses caching.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use SolidWP\Performance\View\Contracts\View;
use SolidWP\Performance\View\Exceptions\FileNotFoundException;

/**
 * Loads an nginx.conf that bypasses caching.
 *
 * @package SolidWP\Performance
 */
final class Bypass_Template_Loader {

	public const VIEW = 'cache/nginx-bypass';

	/**
	 * @var View
	 */
	private View $view;

	/**
	 * @param View $view The view renderer.
	 */
	public function __construct(
		View $view
	) {
		$this->view = $view;
	}

	/**
	 * Get our nginx-bypass config template, with the dynamic values replaced.
	 *
	 * @throws FileNotFoundException If we can't find the view file.
	 *
	 * @return string
	 */
	public function get(): string {
		return $this->view->render_to_string( self::VIEW );
	}
}
