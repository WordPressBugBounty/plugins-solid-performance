<?php
/**
 * A redirect client Symfony HTTP Client decorator to handle redirects
 * when preloading.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Niladam\Uri\Uri;
use SolidWP\Performance\Psr\Log\LoggerAwareInterface;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\HttpClient\AsyncDecoratorTrait;
use SolidWP\Performance\Symfony\Component\HttpClient\Exception\TransportException;
use SolidWP\Performance\Symfony\Component\HttpClient\Response\AsyncContext;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ChunkInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\HttpClientInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ResponseInterface;
use SolidWP\Performance\Symfony\Component\HttpClient\Response\AsyncResponse;

/**
 * A redirect client Symfony HTTP Client decorator to handle redirects
 * when preloading.
 *
 * @method  withOptions( array $options )
 *
 * @package SolidWP\Performance
 */
final class Redirect_Client implements HttpClientInterface, LoggerAwareInterface {

	use AsyncDecoratorTrait;

	/**
	 * @var LoggerInterface|null
	 */
	private ?LoggerInterface $logger = null;

	/**
	 * Handle redirects before passing the request to the next HTTP client.
	 *
	 * @param string $method The request method.
	 * @param string $url The relative URL path.
	 * @param array  $options The Symfony HTTP Client options.
	 *
	 * @return ResponseInterface
	 */
	public function request( string $method, string $url, array $options = [] ): ResponseInterface {
		// Disable redirects.
		$options['max_redirects'] = 0;

		$passthru = function ( ChunkInterface $chunk, AsyncContext $context ) use ( $method, &$options, $url ) {
			if ( ! $context->getInfo( 'redirect_url' ) ) {
				yield $chunk;

				return;
			}

			$original_uri = Uri::of( $context->getInfo( 'url' ) );
			$redirect_uri = Uri::of( $context->getInfo( 'redirect_url' ) );

			$this->logger && $this->logger->debug(
				'Redirect detected',
				[
					'original_path' => $url,
					'original_url'  => (string) $original_uri,
					'redirect_url'  => (string) $redirect_uri,
					'info'          => $context->getInfo(),
				]
			);

			if ( $original_uri->host() !== $redirect_uri->host() ) {
				$this->logger && $this->logger->error(
					'Redirect Error: URL would redirect offsite, skipping!',
					[
						'original_path' => $url,
						'original_url'  => (string) $original_uri,
						'redirect_url'  => (string) $redirect_uri,
						'info'          => $context->getInfo(),
					]
				);

				$context->passthru();

				throw new TransportException( 'Redirect URL would redirect offsite, skipping: ' . $redirect_uri );
			}

			if ( str_contains( (string) $redirect_uri, WP_CONTENT_URL ) ) {
				$this->logger && $this->logger->error(
					'Redirect Error: URL contains the WP_CONTENT_URL, skipping!',
					[
						'original_path' => $url,
						'original_url'  => (string) $original_uri,
						'redirect_url'  => (string) $redirect_uri,
						'info'          => $context->getInfo(),
					]
				);

				$context->passthru();

				throw new TransportException( 'Redirect URL contains the WP_CONTENT_URL, skipping: ' . $redirect_uri );
			}

			// Allow redirects again.
			unset( $options['max_redirects'] );

			$this->logger && $this->logger->debug(
				'Allowing redirect to: {redirect_uri}',
				[
					'redirect_uri' => $redirect_uri,
				] 
			);

			// Replace the request with the allowed redirect URL.
			$context->replaceRequest( $method, (string) $redirect_uri, $options );
		};

		return new AsyncResponse( $this->client, $method, $url, $options, $passthru );
	}

	/**
	 * Set the logger.
	 *
	 * @param LoggerInterface $logger The logger.
	 *
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;

		// Set the logger on the underlying HTTP client as well.
		if ( $this->client instanceof LoggerAwareInterface ) {
			$this->client->setLogger( $logger );
		}
	}
}
