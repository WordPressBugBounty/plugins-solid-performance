<?php
/**
 * The preload scheduler which manages scheduling preloading.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use Countable;
use InvalidArgumentException;
use SolidWP\Performance\Lock\Contracts\Blockable_Lock;
use SolidWP\Performance\Preload\Exceptions\PreloaderInProgressException;
use SolidWP\Performance\Preload\Exceptions\PreloadException;
use SolidWP\Performance\Preload\Monitor\Exceptions\PreloadMonitorMaxRetriesException;
use SolidWP\Performance\Preload\Monitor\Monitor;
use SolidWP\Performance\Preload\Sitemap\Sitemap_Service;
use SolidWP\Performance\Preload\State\State;
use SolidWP\Performance\Preload\Traits\With_Request_Params;
use SolidWP\Performance\Preload\Traits\With_Timeout;
use SolidWP\Performance\Psr\Log\LoggerInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TimeoutExceptionInterface;
use Throwable;

/**
 * The preload scheduler which manages scheduling preloading.
 *
 * @package SolidWP\Performance
 */
class Preload_Scheduler implements Countable {

	use With_Timeout;
	use With_Request_Params;

	/**
	 * @var Blockable_Lock
	 */
	private Blockable_Lock $lock;

	/**
	 * @var Sitemap_Service
	 */
	private Sitemap_Service $sitemap;

	/**
	 * @var Client
	 */
	private Client $client;

	/**
	 * @var State
	 */
	private State $state;

	/**
	 * @var Monitor
	 */
	private Monitor $monitor;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @var Trigger_Url
	 */
	private Trigger_Url $trigger_url;

	/**
	 * @param Blockable_Lock  $lock The preloading lock.
	 * @param Sitemap_Service $sitemap The sitemap service.
	 * @param Client          $client The preloading client.
	 * @param State           $state The preloader state.
	 * @param Monitor         $monitor The preloader monitor.
	 * @param LoggerInterface $logger The logger.
	 * @param Trigger_Url     $trigger_url The home URL to use to trigger a preloader batch.
	 */
	public function __construct(
		Blockable_Lock $lock,
		Sitemap_Service $sitemap,
		Client $client,
		State $state,
		Monitor $monitor,
		LoggerInterface $logger,
		Trigger_Url $trigger_url
	) {
		$this->lock        = $lock;
		$this->sitemap     = $sitemap;
		$this->client      = $client;
		$this->state       = $state;
		$this->monitor     = $monitor;
		$this->logger      = $logger;
		$this->trigger_url = $trigger_url;
	}

	/**
	 * Start the preloading process.
	 *
	 * @param bool $force Whether we are force preloading the entire site.
	 *
	 * @phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
	 *
	 * @throws PreloaderInProgressException If a preloader is already running.
	 * @throws PreloadException If queuing the batch fails.
	 * @throws InvalidArgumentException If an invalid source passed.
	 *
	 * @return void
	 */
	public function start( bool $force = false ): void {
		$this->logger->debug( 'Starting preloader...', [ 'force' => $force ] );

		if ( ! $this->lock->acquire() ) {
			throw new PreloaderInProgressException(
				sprintf(
					/* translators: %s: The source of what initiated or canceled the preloader. */
					__( 'A preloader is already running via "%s"', 'solid-performance' ),
					$this->lock->owner()
				)
			);
		}

		$this->state->start( $this->lock->owner(), $force );
		$this->sitemap->crawl();
		$this->queue_batch();

		$this->logger->debug(
			'Preloader started',
			[
				'owner' => $this->lock->owner(),
			]
		);
	}

	/**
	 * Starts the next batch of preloading.
	 *
	 * @param  string $url The URL to use to initiate the batch of URLs to preload.
	 *
	 * @throws PreloadException If queuing the batch failed.
	 *
	 * @return void
	 */
	public function queue_batch( string $url = '' ): void {
		$url = $url ?: $this->trigger_url->get();

		$this->logger->debug( 'Queuing batch', [ 'url' => $url ] );

		if ( ! $this->is_running() ) {
			return;
		}

		try {
			$response = $this->client->get(
				$url,
				$this->get_preload_trigger_params(),
				[
					'timeout' => $this->get_request_timeout(),
				]
			);

			$response->getContent();

			$response->cancel();
		} catch ( TimeoutExceptionInterface $e ) {
			// We expect the request to throw a timeout exception.
			$this->logger->debug( $e->getMessage() );
		} catch ( Throwable $e ) {
			throw new PreloadException( $e->getMessage(), $e->getCode(), $e );
		}
	}

	/**
	 * Check if the preloader is currently running.
	 *
	 * @return bool
	 */
	public function is_running(): bool {
		return $this->lock->is_acquired();
	}

	/**
	 * Count the number of URLs we're going to preload.
	 *
	 * This includes any URLs excluded by Solid Performance, so it's
	 * not 100% accurate.
	 *
	 * @return int
	 */
	public function count(): int {
		return $this->sitemap->count();
	}

	/**
	 * Mark the preloader as completed.
	 *
	 * @throws PreloadException If we fail to mark the preloader as completed.
	 *
	 * @return self
	 */
	public function complete(): self {
		$this->logger->debug( 'Completing preloader.' );

		try {
			$this->lock->force_release();
			$this->sitemap->clear();
			$this->state->complete();
			$this->monitor->clean();
		} catch ( Throwable $e ) {
			throw new PreloadException( $e->getMessage(), $e->getCode(), $e );
		}

		return $this;
	}

	/**
	 * Cancel the preloader, keep in mind any existing
	 * requests in the current batch will continue to complete.
	 *
	 * @throws PreloadException If we fail to cancel the preloader.
	 *
	 * @return self
	 */
	public function cancel(): self {
		$this->logger->debug( 'Canceling preloader.' );

		try {
			$this->lock->force_release();
			$this->sitemap->clear();
			$this->state->cancel();
			$this->monitor->clean();
		} catch ( Throwable $e ) {
			throw new PreloadException( $e->getMessage(), $e->getCode(), $e );
		}

		return $this;
	}

	/**
	 * Mark the preloader as failed.
	 *
	 * @param string|null $debug_message An optional debug message.
	 *
	 * @throws PreloadException If something goes wrong when failing the preloader.
	 *
	 * @return self
	 */
	public function fail( ?string $debug_message = null ): self {
		$this->logger->debug(
			'Failing preloader: {reason}',
			[
				'reason' => $debug_message,
			]
		);

		try {
			$this->lock->force_release();
			$this->sitemap->clear();
			$this->state->fail();
			$this->monitor->clean();
		} catch ( Throwable $e ) {
			throw new PreloadException( $e->getMessage(), $e->getCode(), $e );
		}

		return $this;
	}

	/**
	 * Get the percentage progress of the preloader.
	 *
	 * @throws PreloadException If the preloader stalled and we can't restart it.
	 * @throws PreloadMonitorMaxRetriesException If we tried to restart the preloader too many times.
	 *
	 * @return int
	 */
	public function progress(): int {
		$total     = $this->sitemap->total();
		$remaining = $this->count();

		if ( ! $total ) {
			return 0;
		}

		$this->logger->debug(
			'Progress remaining URLs: {remaining}',
			[
				'total'     => $total,
				'remaining' => $remaining,
			]
		);

		// Check if the preloader is stalled and restart it.
		$this->is_stalled( $remaining );

		// Preloading complete.
		if ( ! $remaining ) {
			return 100;
		}

		$progress = (int) ceil( ( ( $total - $remaining ) / $total ) * 100 );

		return min( $progress, 100 );
	}

	/**
	 * Check if the preloader is stalled and try to restart it.
	 *
	 * @param int|null $remaining_urls The remaining URL count.
	 *
	 * @throws PreloadException If the preloader stalled and we can't restart it.
	 * @throws PreloadMonitorMaxRetriesException If we tried to restart the preloader too many.
	 *
	 * @return bool
	 */
	public function is_stalled( ?int $remaining_urls = null ): bool {
		$remaining_urls ??= $this->count();

		$this->logger->debug(
			'Checking if preloader is stalled with {remaining_urls} URLs remaining',
			[
				'remaining_urls' => $remaining_urls,
			]
		);

		$is_stalled = $this->monitor->is_stalled( $remaining_urls );

		if ( $is_stalled ) {
			$this->logger->warning( 'Preloader stalled, attempting a retry' );

			$this->queue_batch();
		}

		return $is_stalled;
	}

	/**
	 * Get the preloader state manager.
	 *
	 * @return State
	 */
	public function state(): State {
		return $this->state;
	}

	/**
	 * Whether we are force preloading the entire site.
	 *
	 * @return bool
	 */
	public function is_forced(): bool {
		return $this->state->get()->force;
	}
}
