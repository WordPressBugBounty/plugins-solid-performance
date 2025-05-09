<?php
/**
 * The DOM modifier, which passes HTML markup to modified through a pipeline.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Dom;

use SolidWP\Performance\Http\Header_Factory;
use SolidWP\Performance\Preload\Contracts\Preloadable;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\StellarWP\Pipeline\Pipeline;
use SolidWP\Performance\StellarWP\SuperGlobals\SuperGlobals;
use SolidWP\Performance\Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * The DOM modifier, which passes HTML markup to modified through a pipeline.
 *
 * @see Provider::register_dom_modifier_pipeline()
 *
 * @package SolidWP\Performance
 */
final class Modifier {

	/**
	 * @var Pipeline
	 */
	private Pipeline $pipeline;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @var Header_Factory
	 */
	private Header_Factory $header_factory;

	/**
	 * @param Pipeline        $pipeline The modifier pipeline.
	 * @param LoggerInterface $logger The logger.
	 * @param Header_Factory  $header_factory The header factory.
	 */
	public function __construct(
		Pipeline $pipeline,
		LoggerInterface $logger,
		Header_Factory $header_factory
	) {
		$this->pipeline       = $pipeline;
		$this->logger         = $logger;
		$this->header_factory = $header_factory;
	}

	/**
	 * Run the buffered HTML through our pipeline and modify where needed.
	 *
	 * @filter solidwp/performance/cache/html
	 *
	 * @param string $html The output buffer HTML.
	 *
	 * @return string The modified html.
	 */
	public function modify( string $html ): string {
		if ( is_404() ) {
			return $html;
		}

		// No reason to parse a trigger request.
		if ( SuperGlobals::get_get_var( Preloadable::PRELOAD_TRIGGER_PARAM ) ) {
			return $html;
		}

		$header = $this->header_factory->make();

		// Only allow text/html content type responses to be modified.
		if ( ! $header->starts_with( 'content-type', 'text/html' ) ) {
			return $html;
		}

		// Check if we have any enabled modifiers before attempting HTML modification.
		$has_enabled = $this->pipeline->send( false )
										->via( 'enabled' )
										->thenReturn();

		if ( ! $has_enabled ) {
			$this->logger->debug( 'DOM Modifier: no pipeline modifiers enabled, skipping.' );

			return $html;
		}

		$crawler = new Crawler( $html );

		/** @var Crawler $modified_crawler */
		$modified_crawler = $this->pipeline->send( $crawler )
											->via( 'modify' )
											->thenReturn();

		try {
			// Return the owner document to ensure we have the doctype and html tags.
			return $modified_crawler->getNode( 0 )->ownerDocument->saveHtml();
		} catch ( Throwable $e ) {
			$this->logger->warning(
				'DOM Modifier: Modified HTML was empty',
				[
					'exception' => $e,
				]
			);

			return $html;
		}
	}
}
