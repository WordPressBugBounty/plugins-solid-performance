<?php
/**
 * The Service Provider for Preloading functionality.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Core;
use SolidWP\Performance\Lock\Contracts\Blockable_Lock;
use SolidWP\Performance\Lock\Lock_Factory;
use SolidWP\Performance\Log\Log;
use SolidWP\Performance\Preload\Contracts\Preloadable;
use SolidWP\Performance\Preload\Limiter\Batch_Limiter;
use SolidWP\Performance\Preload\Limiter\Core_Counter;
use SolidWP\Performance\Preload\Limiter\Sleep_Limiter;
use SolidWP\Performance\Preload\Monitor\Monitor;
use SolidWP\Performance\Preload\Sitemap\Repositories\Contracts\Sitemap_Repository;
use SolidWP\Performance\Preload\Sitemap\Repositories\Sitemap_Option_Repository;
use SolidWP\Performance\Preload\Sitemap\Sitemap;
use SolidWP\Performance\Preload\State\Enums\Source;
use SolidWP\Performance\Preload\State\State;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Component\HttpClient\HttpClient;
use SolidWP\Performance\Symfony\Component\HttpClient\ScopingHttpClient;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TimeoutExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * The Service Provider for Preloading functionality.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	public const PRELOADER_LOCK = 'solid_performance.preload.preloader_lock';

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_http_client();
		$this->register_preload_trigger_url();
		$this->register_sitemap_repository();
		$this->register_preload_monitor();
		$this->register_rate_limiter();
		$this->register_preloader();
		$this->register_preload_scheduler();
	}

	/**
	 * Register the underlying Solid Performance Preloader HTTP Client.
	 *
	 * @return void
	 */
	private function register_http_client(): void {
		$this->container->singleton(
			HttpClientInterface::class,
			function () {
				$options = [
					'headers'     => [
						'User-Agent' => sprintf(
							'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 (compatible; Solid Performance/%s)',
							$this->container->get( Core::PLUGIN_VERSION )
						),
					],
					'verify_peer' => apply_filters( 'https_local_ssl_verify', false ),
					'verify_host' => apply_filters( 'https_local_ssl_verify', false ),
				];

				$proxy_url = $this->container->get( Proxy::class )->url();

				if ( $proxy_url ) {
					$options['proxy'] = $proxy_url;
				}

				$logger = $this->container->get( LoggerInterface::class );

				$client = ScopingHttpClient::forBaseUri( HttpClient::create(), home_url(), $options );

				$redirect_client = new Redirect_Client( $client );
				$redirect_client->setLogger( $logger );

				return $redirect_client;
			}
		);
	}

	/**
	 * Register the URL to use to trigger to a preload batch.
	 *
	 * @return void
	 */
	private function register_preload_trigger_url(): void {
		/**
		 * Filter the URL to use to trigger a preload batch.
		 *
		 * @param string $trigger_url The URL to use to trigger a preload batch.
		 */
		$trigger_url = apply_filters( 'solidwp/performance/preload/trigger_url', home_url( '/' ) );

		$this->container->when( Trigger_Url::class )
						->needs( '$trigger_url' )
						->give( static fn(): string => $trigger_url );

		$this->container->singleton( Trigger_Url::class, Trigger_Url::class );
	}

	/**
	 * Register the concrete repository to use for the Sitemap Repository.
	 *
	 * @return void
	 */
	private function register_sitemap_repository(): void {
		$this->container->when( Sitemap::class )
						->needs( '$url' )
						->give(
							function (): string {
								// We assume this automatically redirects to the correct sitemap.
								return home_url( '/sitemap.xml' );
							}
						);

		$this->container->bind( Sitemap_Repository::class, Sitemap_Option_Repository::class );
	}

	/**
	 * Register the preload monitor to check for a stalled preloader.
	 *
	 * @return void
	 */
	private function register_preload_monitor(): void {
		/**
		 * Filter how many seconds to wait before we check for a stalled preloader.
		 *
		 * @param int $timeout The timeout in seconds.
		 */
		$timeout = (int) apply_filters( 'solidwp/performance/preload/monitor/timeout', 12 );

		/**
		 * Filter the maximum number of times we retry a stalled preloader before giving up.
		 *
		 * @param int $max_retries The number of retries.
		 */
		$max_retries = (int) apply_filters( 'solidwp/performance/preload/monitor/max_retries', 10 );

		$this->container->when( Monitor::class )
						->needs( '$timeout' )
						->give( static fn(): int => $timeout );

		$this->container->when( Monitor::class )
						->needs( '$max_retries' )
						->give( static fn(): int => $max_retries );
	}

	/**
	 * Register the rate limiter that controls the batch size of the preloader.
	 *
	 * @return void
	 */
	private function register_rate_limiter(): void {
		$this->container->singleton( Core_Counter::class, Core_Counter::class );
		$this->container->singleton( Sleep_Limiter::class, Sleep_Limiter::class );
		$this->container->singleton( Batch_Limiter::class, Batch_Limiter::class );

		/**
		 * Filters the maximum load per CPU core before we start rate limiting requests.
		 *
		 * 0-0.7 per core is optimal range, allowing headroom.
		 * 0.7-1.0 per core is high, but manageable.
		 * 1.0+ per core indicates CPU is likely bottle necked.
		 *
		 * @param float $max_load_per_core The maximum load per core before we begin rate limiting preloading.
		 */
		$max_load_per_core = apply_filters( 'solidwp/performance/preload/max_load_per_core', 0.5 );

		/**
		 * Filters the delay multiplier to calculate the sleep time.
		 *
		 * @param float $delay The delay multiplier to determine how much more sleep based on system load.
		 */
		$delay = apply_filters( 'solidwp/performance/preload/sleep_multiplier', 1.5 );

		$this->container->when( Sleep_Limiter::class )
						->needs( '$max_load_per_core' )
						->give( static fn(): float => $max_load_per_core );

		$this->container->when( Sleep_Limiter::class )
						->needs( '$delay' )
						->give( static fn(): float => $delay );

		/**
		 * Filter how many URLs are processed per batch.
		 *
		 * @param int $batch_size The number of URLs to process per batch.
		 */
		$batch_size = apply_filters( 'solidwp/performance/preload/batch_size', 50 );

		/**
		 * Filter the decay value for how much we'll reduce the batch size when server load is high.
		 *
		 * @param float $decay The negative value used to calculate exponential decay.
		 */
		$decay = apply_filters( 'solidwp/performance/preload/decay', -0.9 );

		$this->container->when( Batch_Limiter::class )
						->needs( '$batch_size' )
						->give( static fn(): int => $batch_size );

		$this->container->when( Batch_Limiter::class )
						->needs( '$max_load_per_core' )
						->give( static fn(): float => $max_load_per_core );

		$this->container->when( Batch_Limiter::class )
						->needs( '$decay' )
						->give( static fn(): float => $decay );
	}

	/**
	 * Register the Preloader.
	 *
	 * @return void
	 */
	private function register_preloader(): void {
		/**
		 * Filters how long to sleep between batches.
		 *
		 * @param int $seconds How to sleep for in seconds.
		 */
		$seconds = apply_filters( 'solidwp/performance/preload/batch/sleep/seconds', 1 );

		$this->container->when( Preloader::class )
						->needs( '$sleep' )
						->give( static fn(): int => $seconds );

		$this->container->singleton( Preloadable::class, Preloader::class );

		add_action( 'template_redirect', $this->container->callback( Preloadable::class, 'preload' ), 0 );

		// Automatically try to preload URLs after they are purged.
		add_action(
			'solidwp/performance/cache/purge/url/after',
			function ( string $url ) {
				$preloader = $this->container->get( Preloadable::class );

				try {
					$preloader->preload_url( $url );
				} catch ( TimeoutExceptionInterface | TransportExceptionInterface $e ) {
					do_action(
						Log::INFO,
						'Timeout for preload {url}',
						[
							'url'       => $url,
							'exception' => $e,
						]
					);
				} catch ( ClientExceptionInterface $e ) {
					do_action(
						Log::WARNING,
						'Failed to preload {url}',
						[
							'url'       => $url,
							'exception' => $e,
						]
					);
				} catch ( Throwable $e ) {
					do_action(
						Log::ERROR,
						'Failed to preload {url}',
						[
							'url'       => $url,
							'exception' => $e,
						]
					);
				}
			},
			20,
			1
		);
	}

	/**
	 * Register the preload scheduler.
	 *
	 * @return void
	 */
	private function register_preload_scheduler(): void {
		$this->container->singleton( State::class, State::class );

		/**
		 * Filter how long the preloader stays locked until it's canceled or completes.
		 *
		 * @param int $ttl How long in seconds the preloader will be locked until it's canceled or completes.
		 */
		$ttl = apply_filters( 'solidwp/performance/preload/lock/ttl', DAY_IN_SECONDS );

		$this->container->singleton(
			self::PRELOADER_LOCK,
			function () use ( $ttl ): Blockable_Lock {
				$owner = Source::detect_source();

				return $this->container->get( Lock_Factory::class )->make( 'is_preloading', $ttl, $owner );
			}
		);

		$this->container->singleton( Preload_Scheduler::class, Preload_Scheduler::class );

		$this->container->when( Preload_Scheduler::class )
						->needs( Blockable_Lock::class )
						->give(
							fn(): Blockable_Lock => $this->container->get( self::PRELOADER_LOCK )
						);
	}
}
