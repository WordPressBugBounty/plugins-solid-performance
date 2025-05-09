<?php
/**
 * Registers Cache Delivery definitions functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery;

use SolidWP\Performance\Cache_Delivery\Htaccess\Reader;
use SolidWP\Performance\Cache_Delivery\Htaccess\Settings_Listener;
use SolidWP\Performance\Cache_Delivery\Htaccess\Writer;
use SolidWP\Performance\Contracts\Service_Provider;
use SolidWP\Performance\Cache_Delivery\Htaccess\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Htaccess\Contracts\Writable;

/**
 * Registers Cache Delivery definitions functionality in the container.
 *
 * @package SolidWP\Performance
 */
final class Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->register_htaccess();
	}

	/**
	 * Register htaccess cache delivery functionality.
	 *
	 * @return void
	 */
	private function register_htaccess(): void {
		$this->container->bind( Writable::class, Writer::class );
		$this->container->bind( Readable::class, Reader::class );

		// Toggle adding/removing our htaccess rules based on settings changes.
		add_action(
			'solidwp/performance/settings/changed',
			$this->container->callback( Settings_Listener::class, 'on_settings_change' ),
			10,
			2,
		);
	}
}
