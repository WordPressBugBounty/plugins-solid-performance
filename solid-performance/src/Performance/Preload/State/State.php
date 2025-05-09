<?php
/**
 * Manages the ongoing state of the preloader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\State;

use InvalidArgumentException;
use SolidWP\Performance\Preload\State\Enums\Source;
use SolidWP\Performance\Preload\State\Enums\Status;
use SolidWP\Performance\Storage\Contracts\Storage;
use SolidWP\Performance\Storage\Exceptions\InvalidKeyException;
use SolidWP\Performance\Timer\Exceptions\InvalidTimerNameException;
use SolidWP\Performance\Timer\Exceptions\NoActiveTimerException;
use SolidWP\Performance\Timer\Timer;

/**
 * Manages the ongoing state of the preloader.
 *
 * @package SolidWP\Performance
 */
class State {

	private const STORAGE_KEY = 'swpsp_preload_state';
	private const TIMER_NAME  = 'preload_timer';
	public const ID_PREFIX    = 'swpsp_preload';

	/**
	 * @var Storage
	 */
	private Storage $storage;

	/**
	 * @var Timer
	 */
	private Timer $timer;

	/**
	 * @param  Storage $storage  The Storage System.
	 * @param  Timer   $timer The timer.
	 */
	public function __construct( Storage $storage, Timer $timer ) {
		$this->storage = $storage;
		$this->timer   = $timer;
	}

	/**
	 * Mark the preloader as started.
	 *
	 * @param string $source The source that started the preloader.
	 * @param bool   $force Whether we are force preloading the entire site.
	 *
	 * @throws InvalidArgumentException If an invalid source is provided.
	 * @return void
	 */
	public function start( string $source, bool $force = false ) {
		if ( ! Source::is_valid( $source ) ) {
			throw new InvalidArgumentException( 'Invalid $source argument' );
		}

		$this->timer->start( self::TIMER_NAME );

		$entry = new State_Entry( $this->id(), $source, Status::RUNNING, $force );

		$this->set( $entry );
	}

	/**
	 * Get the state entry.
	 *
	 * @throws InvalidKeyException If an invalid storage key is used.
	 * @throws InvalidArgumentException If an invalid source or status is provided.
	 *
	 * @return State_Entry
	 */
	public function get(): State_Entry {
		return $this->storage->get( self::STORAGE_KEY ) ?? new State_Entry(
			$this->id(),
			Source::detect_source(),
			Status::IDLE
		);
	}

	/**
	 * Mark the preloader as canceled.
	 *
	 * @throws InvalidArgumentException If an invalid source or status is provided.
	 * @throws InvalidKeyException If an invalid storage key is used.
	 *
	 * @return bool
	 */
	public function cancel(): bool {
		$this->timer->clear( self::TIMER_NAME );

		$entry = $this->get();

		$entry->status = Status::CANCELED;
		$entry->source = Source::detect_source();

		return $this->set( $entry );
	}

	/**
	 * Mark the preloader as complete.
	 *
	 * @throws InvalidArgumentException If an invalid source or status is provided.
	 * @throws InvalidKeyException If an invalid storage key is used.
	 * @throws InvalidTimerNameException If an invalid timer name is provided.
	 * @throws NoActiveTimerException If the timer hasn't been started yet.
	 *
	 * @return bool
	 */
	public function complete(): bool {
		$entry = $this->get();

		$entry->status   = Status::COMPLETED;
		$entry->duration = $this->timer->stop( self::TIMER_NAME )->format();

		return $this->set( $entry );
	}

	/**
	 * Mark a preloader as failed.
	 *
	 * @throws InvalidArgumentException If an invalid source or status is provided.
	 * @throws InvalidKeyException If an invalid storage key is used.
	 *
	 * @return bool
	 */
	public function fail(): bool {
		$this->timer->clear( self::TIMER_NAME );

		$entry = $this->get();

		$entry->status = Status::FAILED;

		return $this->set( $entry );
	}

	/**
	 * Set the current state.
	 *
	 * @param State_Entry $entry The state entry.
	 *
	 * @throws InvalidKeyException If an invalid storage key is provided.
	 *
	 * @return bool
	 */
	private function set( State_Entry $entry ): bool {
		return $this->storage->set( self::STORAGE_KEY, $entry );
	}

	/**
	 * Generate a unique ID for the current preloader.
	 *
	 * @return string
	 */
	private function id(): string {
		return uniqid( self::ID_PREFIX );
	}
}
