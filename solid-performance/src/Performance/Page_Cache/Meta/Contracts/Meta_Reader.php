<?php
/**
 * The meta reader contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Contracts;

use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotReadableException;
use SolidWP\Performance\Page_Cache\Meta\Meta;

/**
 * @internal
 */
interface Meta_Reader {

	/**
	 * Read the data from a meta file and return a Meta value object.
	 *
	 * @param string $url The URL associated with the metadata.
	 *
	 * @throws MetadataNotReadableException If we're unable to read the metadata.
	 *
	 * @return Meta
	 */
	public function read( string $url ): Meta;
}
