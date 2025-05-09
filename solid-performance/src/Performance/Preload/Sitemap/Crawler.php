<?php
/**
 * The Sitemap URL crawler which collects all URLs found in
 * all sitemaps.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Sitemap;

use SolidWP\Performance\Preload\Client;
use SolidWP\Performance\Preload\Sitemap\Exceptions\CrawlException;
use SolidWP\Performance\Symfony\Component\DomCrawler\Crawler as Dom_Crawler;

/**
 * The Sitemap URL crawler which collects all URLs found in
 * all sitemaps.
 *
 * @package SolidWP\Performance
 */
final class Crawler {

	/**
	 * The Preloader Client.
	 *
	 * @var Client
	 */
	private Client $client;

	/**
	 * The Sitemap Object.
	 *
	 * @var Sitemap
	 */
	private Sitemap $sitemap;

	/**
	 * The list of collected URLs.
	 *
	 * @var string[]
	 */
	private array $urls = [];

	/**
	 * The list of collected sub-sitemap URls.
	 *
	 * @var string[]
	 */
	private array $sitemaps = [];

	/**
	 * @param  Client  $client The Preloader Client.
	 * @param  Sitemap $sitemap The Sitemap Object.
	 */
	public function __construct( Client $client, Sitemap $sitemap ) {
		$this->client  = $client;
		$this->sitemap = $sitemap;
	}

	/**
	 * The list of all URLs found in all sitemaps.
	 *
	 * @return string[]
	 */
	public function urls(): array {
		return array_unique( $this->urls );
	}

	/**
	 * Recursively crawl sitemaps and collect their URLs.
	 *
	 * @throws CrawlException If we failed to crawl the sitemap.
	 *
	 * @return Crawler
	 */
	public function crawl(): self {
		// We haven't collected the sub-sitemap.xml URLs yet.
		if ( ! $this->sitemaps ) {
			$response = $this->client->get( $this->sitemap->get_url() );

			$content = $response->getContent( false );

			if ( ! $content ) {
				throw new CrawlException( 'The sitemap did not return any content' );
			}

			$this->extract_sitemaps( $content );

			if ( ! $this->sitemaps ) {
				// If we didn't find any sitemaps, see if these are normal site URLs.
				$this->extract_urls( $content );

				if ( ! $this->urls ) {
					throw new CrawlException( 'The sitemap did not contain any sitemap URLs' );
				}

				// If we made it here, we have the only URLs we found, bail to prevent infinite loops.
				return $this;
			}

			// Recursively call this method to now collect the URLs.
			$this->crawl();
		} else {
			$responses = [];

			// Create concurrent requests for all sub-sitemap URLs.
			foreach ( $this->sitemaps as $sitemap_url ) {
				$responses[] = $this->client->get( $sitemap_url );
			}

			// Extract all the URLs from the sub-sitemaps.
			foreach ( $responses as $response ) {
				$content = $response->getContent( false );

				if ( ! $content ) {
					continue;
				}

				$this->extract_urls( $content );
			}
		}

		return $this;
	}

	/**
	 * Extract all sitemap URLs from a sitemap.xml response.
	 *
	 * @param  string $content The response content.
	 *
	 * @return void
	 */
	private function extract_sitemaps( string $content ) {
		$crawler = new Dom_Crawler( $content );
		$crawler->registerNamespace( 'sm', 'http://www.sitemaps.org/schemas/sitemap/0.9' );

		// Check for sub-sitemap URLS without an XML namespace first.
		$this->sitemaps = $crawler->filterXPath( '//sitemap//loc' )->each( static fn( Dom_Crawler $node ) => $node->text() );

		// Check with the namespace.
		if ( ! $this->sitemaps ) {
			$this->sitemaps = $crawler->filterXPath( '//sm:sitemap//loc' )->each( static fn( Dom_Crawler $node ) => $node->text() );
		}

		$crawler->clear();
	}

	/**
	 * Extract non-sitemap URLs from a sitemap.xml response.
	 *
	 * @param  string $content The response content.
	 *
	 * @return void
	 */
	private function extract_urls( string $content ) {
		$crawler = new Dom_Crawler( $content );
		$crawler->registerNamespace( 'sm', 'http://www.sitemaps.org/schemas/sitemap/0.9' );

		// Check for sitemap URLs without an XML namespace first.
		$urls = $crawler->filterXPath( '//loc' )->each( static fn( Dom_Crawler $node ) => $node->text() );

		// Check with the namespace.
		if ( ! $urls ) {
			$urls = $crawler->filterXPath( '//sm:loc' )->each( static fn( Dom_Crawler $node ) => $node->text() );
		}

		$this->urls = array_merge( $this->urls, $urls );

		$crawler->clear();
	}
}
