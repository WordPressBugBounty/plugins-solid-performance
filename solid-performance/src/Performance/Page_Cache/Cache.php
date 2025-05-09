<?php
/**
 * Handles all functionality related to storing and returning files in the page cache directory.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache;

use Exception;
use SolidWP\Performance\Container;
use SolidWP\Performance\Http\Header;
use SolidWP\Performance\Http\Request;
use SolidWP\Performance\Page_Cache\Meta\Meta_Manager;
use SolidWP\Performance\Pipelines\Page_Cache;
use SolidWP\Performance\StellarWP\Pipeline\Pipeline;
use SolidWP\Performance\StellarWP\SuperGlobals\SuperGlobals;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles all functionality related to storing and returning files in the page cache directory.
 *
 * @since 0.1.0
 *
 * @package SolidWP\Performance
 */
class Cache {

	/**
	 * The current request.
	 *
	 * @since 0.1.0
	 *
	 * @var Request
	 */
	private Request $request;

	/**
	 * @var Container
	 */
	private Container $container;

	/**
	 * @var Cache_Handler
	 */
	private Cache_Handler $handler;

	/**
	 * @var Meta_Manager
	 */
	private Meta_Manager $meta_manager;

	/**
	 * @var Header_Sender
	 */
	private Header_Sender $header_sender;

	/**
	 * The pipes to send all requests through before serving an existing cached page.
	 *
	 * @context pre-wordpress
	 *
	 * @since 0.1.0
	 *
	 * @var array<int,string>
	 */
	private array $serve_pipes = [
		Page_Cache\Response_Code::class,
		Page_Cache\Constant::class,
		Page_Cache\Admin::class,
		Page_Cache\Authenticated::class,
		Page_Cache\Method::class,
		Page_Cache\Path::class,
		Page_Cache\Query::class,
		Page_Cache\Exclusion::class,

		// Third Party Exclusions.
		Page_Cache\Third_Party_Exclusions\GiveWP::class,
		Page_Cache\Third_Party_Exclusions\WooCommerce::class,

		Page_Cache\Preload_Response::class,
	];

	/**
	 * The pipes to send new requests through before saving them to the cache directory.
	 *
	 * @context after-wordpress
	 *
	 * @since 0.1.0
	 *
	 * @var array<int,string>
	 */
	private array $save_pipes = [
		Page_Cache\Response_Code::class,
		Page_Cache\Constant::class,
		Page_Cache\Admin::class,
		Page_Cache\Authenticated::class,
		Page_Cache\Content_Type::class,
		Page_Cache\Wp::class,
		Page_Cache\Method::class,
		Page_Cache\Path::class,
		Page_Cache\Post::class,
		Page_Cache\Exclusion::class,
		Page_Cache\Query::class,

		// Third Party Exclusions.
		Page_Cache\Third_Party_Exclusions\GiveWP::class,
		Page_Cache\Third_Party_Exclusions\WooCommerce::class,

		Page_Cache\Preload_Request::class,
	];

	/**
	 * The class constructor.
	 *
	 * @param Request       $request The current request.
	 * @param Container     $container The DI container.
	 * @param Cache_Handler $handler The Cache Handler.
	 * @param Meta_Manager  $meta_manager The meta manager.
	 * @param Header_Sender $header_sender The header sender.
	 *
	 * @since 0.1.0
	 */
	public function __construct(
		Request $request,
		Container $container,
		Cache_Handler $handler,
		Meta_Manager $meta_manager,
		Header_Sender $header_sender
	) {
		$this->request       = $request;
		$this->container     = $container;
		$this->handler       = $handler;
		$this->meta_manager  = $meta_manager;
		$this->header_sender = $header_sender;
	}

	/**
	 * Returns the cached file to the browser if it exists.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function serve_cached_file(): void {
		if ( ! $this->should_serve_cached_file() ) {
			return;
		}

		// Cache file doesn't exist or is expired.
		if ( ! $this->handler->is_valid() ) {
			return;
		}

		$cache_file = $this->handler->get_file_path();

		// Get file modification time.
		$mod_time = filemtime( $cache_file );

		$header = $this->meta_manager->read( $this->request->uri )->headers ?? new Header();

		// Add custom headers.
		$header->set( 'Last-Modified', gmdate( 'D, d M Y H:i:s', $mod_time ) . ' GMT' );
		$header->set( 'X-Cache-Age', time() - $mod_time );
		$header->set( 'X-Cache', 'HIT' );
		$header->set( 'X-Cached-By', 'Solid Performance' );

		if ( ! $header->has( 'Vary' ) ) {
			$header->set( 'Vary', 'Accept-Encoding' );
		}

		$modified_since = SuperGlobals::get_server_var( 'HTTP_IF_MODIFIED_SINCE', '' );
		$is_304         = $modified_since && strtotime( $modified_since ) === $mod_time;

		// Cache is current, just respond with 304 headers.
		if ( $is_304 ) {
			// No Content-Encoding headers for 304 response.
			$header->remove( 'Content-Encoding' );
			$header->set( 'Expires', gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
			$header->set( 'Cache-Control', 'no-cache, must-revalidate' );

			// Send cached metadata and 304 headers.
			$this->header_sender->send( $header );
			header( SuperGlobals::get_server_var( 'SERVER_PROTOCOL', '' ) . ' 304 Not Modified', true, 304 );
			exit;
		}

		$encoding = $this->handler->compressor()->encoding();

		// Add Content-Encoding headers for compression (these should not be sent with a 304).
		if ( $encoding ) {
			$header->set( 'Content-Encoding', $encoding );
		}

		// Send cached metadata headers.
		$this->header_sender->send( $header );

		readfile( $cache_file );
		exit;
	}

	/**
	 * Saves content to a new file in the cache.
	 *
	 * @since 0.1.0
	 *
	 * @throws Exception When cache cannot be saved or the cache directory can't be created.
	 *
	 * @param string $output Output to save in the cached file.
	 *
	 * @return void
	 */
	public function save_output( string $output ): void {
		// Don't save empty buffers.
		if ( $output === '' ) {
			return;
		}

		// Check if the current request should be cached.
		if ( ! $this->should_save_cached_file() ) {
			return;
		}

		$this->handler->save( $output );

		/*
		 * If we're saving a cache file, this is going to be a cache miss.
		 */
		if ( ! headers_sent() ) {
			header( 'X-Cache: MISS' );
		}
	}

	/**
	 * Determines if the current request should be served an existing cached page.
	 *
	 * If any pipe returns false, the request will not be served from existing cache.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	private function should_serve_cached_file(): bool {
		$pipeline = new Pipeline( $this->container );
		$context  = new WP_Context( $this->request );

		return $pipeline
				->send( $context )
				->through( $this->serve_pipes )
				->then(
					function () {
						// If we make it through the pipeline, we should cache the request.
						return true;
					}
				);
	}

	/**
	 * Run through pipeline and determine if the current request should be saved to the cache directory.
	 *
	 * If any middleware return false, the current request will not be cached (or served from existing cache).
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	private function should_save_cached_file(): bool {
		$pipeline = new Pipeline( $this->container );
		$context  = $this->container->get( WP_Context::class );

		return $pipeline
				->send( $context )
				->through( $this->save_pipes )
				->then(
					function () use ( $context ) {
						/**
						 * If the request makes it through this pipeline, the request should be saved.
						 *
						 * However, since this is fired within a loaded WordPress context, developers can optionally hook in and determine if the response should be saved.
						 *
						 * @param WP_Context $context The context of the current request.
						 *
						 * @return bool
						 */
						return apply_filters( 'solidwp/performance/should_save_cached_file', true, $context );
					}
				);
	}
}
