<?php
/**
 * The Preloader client.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\HttpClientInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The Preloader client.
 *
 * @package SolidWP\Performance
 */
class Client {

	/**
	 * The underlying HTTP client.
	 *
	 * @var HttpClientInterface
	 */
	private HttpClientInterface $client;

	/**
	 * @var Uri
	 */
	private Uri $uri;

	/**
	 * @param HttpClientInterface $client The underlying HTTP client.
	 * @param Uri                 $uri The URI object.
	 */
	public function __construct( HttpClientInterface $client, Uri $uri ) {
		$this->client = $client;
		$this->uri    = $uri;
	}

	/**
	 * Get the underlying HTTP client.
	 *
	 * @return HttpClientInterface
	 */
	public function client(): HttpClientInterface {
		return $this->client;
	}

	/**
	 * Perform a GET request.
	 *
	 * @param  string                $path     The relative path to access to the WordPress home URL.
	 * @param  array<string, mixed>  $params   The query string parameters to pass to the request.
	 * @param  array<string, string> $headers  Additional headers to send along with the request.
	 * @param  array<string, mixed>  $options  Additional Symfony HTTP Client options.
	 *
	 * @throws TransportExceptionInterface When an error occurs at the transport level, e.g. DNS failure, network failure etc...
	 *
	 * @return ResponseInterface
	 */
	public function get( string $path, array $params = [], array $headers = [], array $options = [] ): ResponseInterface {
		return $this->request(
			$path,
			'GET',
			array_merge(
				$options,
				[
					'query'   => $params,
					'headers' => $headers,
				],
			)
		);
	}

	/**
	 * Send a request.
	 *
	 * @param  string               $path The relative path or URL to make relative to access to the WordPress home URL.
	 * @param  string               $method The Request Method, e.g. GET, POST, DELETE etc...
	 * @param  array<string, mixed> $options Additional Symfony HTTP Client options.
	 *
	 * @throws TransportExceptionInterface When an error occurs at the transport level, e.g. DNS failure, network failure etc...
	 *
	 * @return ResponseInterface
	 */
	public function request( string $path, string $method = 'GET', array $options = [] ): ResponseInterface {
		$uri = $this->uri->make_relative( $path );

		return $this->client->request( $method, $uri, $options );
	}
}
