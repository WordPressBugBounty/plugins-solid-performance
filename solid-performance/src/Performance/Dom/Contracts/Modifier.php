<?php
/**
 * The pipeline dom modifier stage contract.
 *
 * Each modifier should implement this interface.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Dom\Contracts;

use Closure;
use SolidWP\Performance\Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
interface Modifier {

	/**
	 * Whether this pipeline step is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool;

	/**
	 * Allows us to first check all Modifiers via the Pipeline to see if any are enabled before proceeding
	 * with DOM modification.
	 *
	 * Should immediately return true if this pipeline stage is enabled, otherwise
	 * pass the current $enabled value to the next stage.
	 *
	 * @see self::is_enabled()
	 *
	 * @param bool    $enabled Whether the previous stage is enabled.
	 * @param Closure $next The closure passed to the next stage.
	 *
	 * @return bool
	 */
	public function enabled( bool $enabled, Closure $next ): bool;

	/**
	 * Handle modifying the HTML and passing it to the next stage of the pipeline,
	 * assuming it's enabled.
	 *
	 * @param Crawler $crawler The symfony dom crawler instance.
	 * @param Closure $next The closure passed to the next stage.
	 *
	 * @return Crawler
	 */
	public function modify( Crawler $crawler, Closure $next ): Crawler;
}
