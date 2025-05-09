<?php
/**
 * Registers Cache Delivery definitions functionality in the container.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery;

use SolidWP\Performance\Contracts\Service_Provider;

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
		$this->register_nginx();
		$this->register_manager_collection();
		$this->register_settings_listener();
	}

	/**
	 * Register htaccess cache delivery functionality.
	 *
	 * @return void
	 */
	private function register_htaccess(): void {
		$this->container->when( Htaccess\Manager::class )
						->needs( Contracts\Writable::class )
						->give( Htaccess\Writer::class );

		$this->container->when( Htaccess\Manager::class )
						->needs( Contracts\Readable::class )
						->give( Htaccess\Reader::class );
	}

	/**
	 * Register Nginx cache delivery functionality.
	 *
	 * @return void
	 */
	private function register_nginx(): void {
		$this->container->when( Nginx\Manager::class )
						->needs( Contracts\Writable::class )
						->give( Nginx\Writer::class );

		$this->container->when( Nginx\Manager::class )
						->needs( Contracts\Readable::class )
						->give( Nginx\Reader::class );
	}

	/**
	 * Register the managers that are part of the Manager Collection.
	 *
	 * @return void
	 */
	private function register_manager_collection(): void {
		$this->container->when( Manager_Collection::class )
			->needs( '$managers' )
			->give(
				fn(): array => [
					// Add any new managers indexed by their type.
					Cache_Delivery_Type::HTACCESS => $this->container->get( Htaccess\Manager::class ),
					Cache_Delivery_Type::NGINX    => $this->container->get( Nginx\Manager::class ),
				]
			);
	}

	/**
	 * Run events when the cache delivery method changes.
	 *
	 * @return void
	 */
	private function register_settings_listener(): void {
		add_action(
			'solidwp/performance/settings/changed',
			$this->container->callback( Settings_Listener::class, 'on_settings_change' ),
			10,
			2,
		);
	}
}
