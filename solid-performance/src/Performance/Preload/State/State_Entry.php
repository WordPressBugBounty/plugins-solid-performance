<?php
/**
 * The State Entry Object. Use the State object to manipulate this.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\State;

use InvalidArgumentException;
use SolidWP\Performance\Preload\State\Enums\Source;
use SolidWP\Performance\Preload\State\Enums\Status;

/**
 * This object represents the current state of the preloader
 * and is stored, retrieved and modified as the preloader changes.
 *
 * @see State
 *
 * @package SolidWP\Performance
 */
final class State_Entry {

	/**
	 * The unique ID of the current preloader.
	 *
	 * @var string
	 */
	public string $id;

	/**
	 * The source that started the preloader.
	 *
	 * @see Source
	 *
	 * @var string
	 */
	public string $source;

	/**
	 * The current status of the preloader.
	 *
	 * @see Status
	 *
	 * @var string
	 */
	public string $status;

	/**
	 * Whether we are force preloading the entire site.
	 *
	 * @var bool
	 */
	public bool $force = false;

	/**
	 * The human-readable duration set when the preloader is complete.
	 *
	 * @var string|null
	 */
	public ?string $duration;

	/**
	 * @param string      $id The unique ID of the current preloader.
	 * @param string      $source The source that started the preloader, e.g. web/cli.
	 * @param string      $status The current status of the preloader.
	 * @param bool        $force Whether we are force preloading the entire site.
	 * @param string|null $duration The human-readable duration set when the preloader is complete.
	 *
	 * @throws InvalidArgumentException If an invalid id, source or status is provided.
	 */
	public function __construct(
		string $id,
		string $source,
		string $status,
		bool $force = false,
		?string $duration = null
	) {
		if ( empty( $id ) ) {
			throw new InvalidArgumentException( 'The $id argument cannot be empty.' );
		}

		if ( ! Source::is_valid( $source ) ) {
			throw new InvalidArgumentException( 'Invalid $source argument.' );
		}

		if ( ! Status::is_valid( $status ) ) {
			throw new InvalidArgumentException( 'Invalid $status argument.' );
		}

		$this->id       = $id;
		$this->source   = $source;
		$this->status   = $status;
		$this->force    = $force;
		$this->duration = $duration;
	}
}
