<?php
/**
 * The Lazy Loading DOM modifier.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Dom\Modifiers;

use Closure;
use InvalidArgumentException;
use LogicException;
use SolidWP\Performance\Assets\Asset;
use SolidWP\Performance\Config\Config;
use SolidWP\Performance\Dom\Contracts\Modifier;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\DomCrawler\Crawler;

/**
 * The Lazy Loading DOM modifier.
 *
 * @package SolidWP\Performance
 */
final class Lazy_Load_Modifier implements Modifier {

	/**
	 * Whether we found CSS background images to process and need to inject
	 * our JS into the markup.
	 *
	 * @var bool
	 */
	private bool $inject_script = false;

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @var Asset
	 */
	private Asset $asset;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Config          $config The config object.
	 * @param Asset           $asset The asset handler.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct( Config $config, Asset $asset, LoggerInterface $logger ) {
		$this->config = $config;
		$this->asset  = $asset;
		$this->logger = $logger;
	}

	/**
	 * Whether this pipeline step is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return (bool) $this->config->get( 'page_cache.lazy_loading.enabled' );
	}

	/**
	 * Return true immediately if we're enabled, otherwise pass the current result to the next stage.
	 *
	 * @param bool    $enabled Whether the previous stage is enabled.
	 * @param Closure $next The closer passed to the next stage.
	 *
	 * @return bool
	 */
	public function enabled( bool $enabled, Closure $next ): bool {
		return $this->is_enabled() ?: $next( $enabled );
	}

	/**
	 * Find elements that have a style tag which contains a `url()` data tag.
	 * Add our custom data attributes and copy the entire style attribute content to another
	 * data attribute in order to be swapped back when it enters the viewport to enable CSS background
	 * lazy loading.
	 *
	 * @param Crawler $crawler The Symfony Crawler instance.
	 * @param Closure $next The next closure to pass in the pipeline.
	 *
	 * @throws InvalidArgumentException When current node is empty.
	 * @throws LogicException If the CssSelector Component is not available.
	 *
	 * @return Crawler
	 */
	public function modify( Crawler $crawler, Closure $next ): Crawler {
		if ( ! $this->is_enabled() ) {
			return $next( $crawler );
		}

		// Iterate through all elements with a style tag that contains a url() data type.
		$crawler->filter( '[style*="url("]' )->each(
			function ( Crawler $node ) {
				$current = $node->getNode( 0 );

				$style = $node->attr( 'style' );

				// Match CSS property:value pairs.
				preg_match_all( '/([\w-]+)\s*:\s*([^;]+)\s*;?/', $style, $matches, PREG_SET_ORDER );

				$style_without_urls = '';
				$style_with_urls    = '';

				foreach ( $matches as $match ) {
					$css_property = trim( $match[1] );
					$css_value    = trim( $match[2] );

					$formatted_style = "$css_property:$css_value;";

					if ( str_contains( $css_value, 'url(' ) ) {
						$style_with_urls .= $formatted_style;
					} else {
						$style_without_urls .= $formatted_style;
					}
				}

				// These must match our attributes from our lazy-load.js script.
				$current->setAttribute( 'style', $style_without_urls );
				$current->setAttribute( 'data-solid-perf-style', $style_with_urls );
				$current->setAttribute( 'data-solid-perf-trigger', 'viewport' );
				$current->setAttribute( 'data-solid-perf-attrs', 'style' );

				$this->inject_script = true;
			}
		);

		if ( $this->inject_script ) {
			$this->inject_lazy_loading_script( $crawler );
		}

		return $next( $crawler );
	}

	/**
	 * Injects our lazy loading script tag just before the closing </body> tag.
	 *
	 * @param Crawler $crawler The crawler instance.
	 *
	 * @throws LogicException If the CssSelector Component is not available.
	 *
	 * @return void
	 */
	private function inject_lazy_loading_script( Crawler $crawler ): void {
		$this->logger->debug( 'DOM Modifier: injecting lazyLoader.js before closing body tag' );

		$body = $crawler->filter( 'body' );

		if ( $body->count() < 0 ) {
			return;
		}

		// Build the script "src" attribute.
		$script_src = sprintf(
			'%s?ver=%s',
			$this->asset->get_url( 'build/lazyLoader.js' ),
			$this->asset->get_meta( 'build/lazyLoader' )['version'] ?? '1.0.0'
		);

		$script_node = $crawler->getNode( 0 )->ownerDocument->createElement( 'script' );
		$script_node->setAttribute( 'src', esc_url( $script_src ) );

		$body->getNode( 0 )->appendChild( $script_node );
	}
}
