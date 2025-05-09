<?php
/**
 * The base URL to use to trigger a preload batch.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload;

use InvalidArgumentException;

/**
 * The base URL to use to trigger a preload batch.
 *
 * @package SolidWP\Performance
 */
final class Trigger_Url {

	/**
	 * @var string
	 */
	private string $trigger_url;

	/**
	 * @param string $trigger_url The base URL to use to trigger a preload batch.
	 *
	 * @throws InvalidArgumentException If the trigger URL is empty.
	 */
	public function __construct( string $trigger_url ) {
		if ( strlen( $trigger_url ) === 0 ) {
			throw new InvalidArgumentException( 'The trigger URL cannot be empty' );
		}

		if ( esc_url_raw( $trigger_url, [ 'http', 'https' ] ) !== $trigger_url ) {
			throw new InvalidArgumentException( 'The trigger URL is not a valid URL' );
		}

		$this->trigger_url = $trigger_url;
	}

	/**
	 * Get the trigger URL.
	 *
	 * @return string
	 */
	public function get(): string {
		return $this->trigger_url;
	}
}
