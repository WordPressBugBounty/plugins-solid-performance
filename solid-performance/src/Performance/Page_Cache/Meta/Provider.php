<?php
/**
 * The Service Provider for cache meta.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Reader;
use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Writer;
use SolidWP\Performance\Page_Cache\Meta\Readers\Array_File_Reader;
use SolidWP\Performance\Page_Cache\Meta\Readers\Sanitized_Meta_Reader;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Collection;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Sanitizers\Header_Sanitizer;
use SolidWP\Performance\Page_Cache\Meta\Writers\Array_File_Writer;
use SolidWP\Performance\Page_Cache\Meta\Writers\Sanitized_Meta_Writer;

/**
 * The Service Provider for cache meta.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const SANITIZERS = 'solid_performance.page_cache.meta.sanitizers';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_sanitizers();
		$this->register_writers();
		$this->register_readers();
	}

	/**
	 * Register meta sanitizers in the container.
	 *
	 * @return void
	 */
	private function register_sanitizers(): void {
		/**
		 * Filters the denied response headers that will not be stored or served during
		 * a cached response.
		 *
		 * @param string[] $denied_headers
		 */
		$denied_headers = apply_filters(
			'solidwp/performance/meta/denied_headers',
			[
				'Connection',
				'Content-Disposition',
				'Content-Length',
				'Pragma',
				'Proxy-Authenticate',
				'Server-Timing',
				'Set-Cookie',
				'Trailer',
				'Transfer-Encoding',
				'Upgrade',
				'Vary',
				'Via',
				'WWW-Authenticate',
				'Warning',
				'X-CSRF-Token',
				'X-Cache',
				'X-WP-Nonce',
				'X-XSRF-Token',
			]
		);

		$this->container->when( Header_Sanitizer::class )
						->needs( '$denied_headers' )
						->give( static fn(): array => $denied_headers );

		$this->container->singleton(
			self::SANITIZERS,
			fn(): array => [
				// You may add other sanitizers here.
				$this->container->get( Header_Sanitizer::class ),
			]
		);

		$this->container->when( Collection::class )
						->needs( '$sanitizers' )
						->give( fn(): array => $this->container->get( self::SANITIZERS ) );
	}

	/**
	 * Register meta writer container definitions.
	 *
	 * @return void
	 */
	private function register_writers(): void {
		$this->container->when( Sanitized_Meta_Writer::class )
						->needs( Meta_Writer::class )
						->give( fn() => $this->container->get( Array_File_Writer::class ) );

		$this->container->bind( Meta_Writer::class, fn() => $this->container->get( Sanitized_Meta_Writer::class ) );
	}

	/**
	 * Register meta reader container definitions.
	 *
	 * @return void
	 */
	private function register_readers(): void {
		$this->container->when( Sanitized_Meta_Reader::class )
						->needs( Meta_Reader::class )
						->give( fn() => $this->container->get( Array_File_Reader::class ) );

		$this->container->bind( Meta_Reader::class, fn() => $this->container->get( Sanitized_Meta_Reader::class ) );
	}
}
