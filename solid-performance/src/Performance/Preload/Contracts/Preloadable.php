<?php
/**
 * The preloading strategy to use.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Contracts;

use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The preloading strategy to use.
 *
 * @internal
 *
 * @package SolidWP\Performance
 */
interface Preloadable {

	/**
	 * The query string parameter to trigger a preload batch.
	 */
	public const PRELOAD_TRIGGER_PARAM    = 'swpsp_trigger_preload';
	public const PRELOAD_CACHE_BUST_PARAM = 'swpsp_cache_bust';
	public const PRELOAD_NO_CACHE_PARAM   = 'nocache';

	/**
	 * Send a preload request.
	 *
	 * @action solidwp/performance/cache/purge/url/after
	 *
	 * @param  string               $url The full or relative URL to preload.
	 * @param  array<string, mixed> $params The query parameters to attach to the request.
	 *
	 * @throws TransportExceptionInterface When an error happens at the transport level.
	 *
	 * @return ResponseInterface
	 */
	public function preload_url( string $url, array $params = [] ): ResponseInterface;

	/**
	 * Initiate site-wide preloading.
	 *
	 * @return void
	 */
	public function preload(): void;
}
