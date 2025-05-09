<?php
/**
 * The Sitemap object.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Sitemap;

/**
 * The Sitemap object.
 *
 * @package SolidWP\Performance
 */
final class Sitemap {

	/**
	 * The default sitemap URL.
	 *
	 * @var string
	 */
	private string $url;

	/**
	 * @param  string $url The default sitemap URL.
	 */
	public function __construct( string $url = 'sitemap.xml' ) {
		$this->url = $url;
	}

	/**
	 * Get the sitemap URL.
	 *
	 * @return string
	 */
	public function get_url(): string {
		return $this->url;
	}
}
