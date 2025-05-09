<?php
/**
 * A custom progress bar that allows setting the current progress
 * instead of just incrementing, which is all WP CLI allows.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\WP_CLI;

use cli\progress\Bar;
use cli\Streams;

/**
 * A custom progress bar that allows setting the current progress
 * instead of just incrementing, which is all WP CLI allows.
 *
 * @package SolidWP\Performance
 */
final class Progress extends Bar {

	/**
	 * Override the current progress and then update the display if enough time has passed
	 * since our last tick.
	 *
	 * @param  int         $current  Set the current progress amount.
	 * @param  string|null $msg      The text to display next to the Notifier. (optional).
	 *
	 * @see Bar::tick()
	 *
	 * @return void
	 */
	public function setProgress( int $current = 1, ?string $msg = null ): void {
		if ( $msg ) {
			$this->_message = $msg;
		}

		$this->_current = $current;

		if ( $this->shouldUpdate() ) {
			Streams::out( "\r" );
			$this->display();
		}
	}
}
