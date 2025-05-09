<?php
/**
 * A collection of Cache Delivery managers, indexed by their cache delivery type.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery;

use SolidWP\Performance\Cache_Delivery\Htaccess\Manager as HtaccessManager;
use SolidWP\Performance\Cache_Delivery\Nginx\Manager as NginxManager;

/**
 * A collection of Cache Delivery managers, indexed by their cache delivery type.
 *
 * @see Cache_Delivery_Type::all()
 * @see Provider::register_manager_collection()
 *
 * @package SolidWP\Performance
 */
final class Manager_Collection {

	/**
	 * The manager instances indexed by their cache delivery type.
	 *
	 * @var array<string, HtaccessManager|NginxManager>
	 */
	private array $managers;

	/**
	 * @param array<string, HtaccessManager|NginxManager> $managers The manager instances.
	 */
	public function __construct( array $managers ) {
		$this->managers = $managers;
	}

	/**
	 * Get a manager by type.
	 *
	 * @see Cache_Delivery_Type::all()
	 *
	 * @param string $type The manager instance to get.
	 *
	 * @return HtaccessManager|NginxManager|null
	 */
	public function get( string $type ) {
		return $this->managers[ $type ] ?? null;
	}
}
