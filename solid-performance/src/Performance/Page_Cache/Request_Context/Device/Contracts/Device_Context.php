<?php
/**
 * Determines a cache file suffix based on a detected device type.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Request_Context\Device\Contracts;

interface Device_Context {

	/**
	 * The cache file suffix.
	 *
	 * @return string
	 */
	public function suffix(): string;
}
