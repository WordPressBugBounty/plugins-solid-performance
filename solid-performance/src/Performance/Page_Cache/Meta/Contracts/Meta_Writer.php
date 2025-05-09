<?php
/**
 * The meta writer contract.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Contracts;

use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotWritableException;
use SolidWP\Performance\Page_Cache\Meta\Meta;

/**
 * @internal
 */
interface Meta_Writer {

	/**
	 * Write a Meta value object's data somewhere.
	 *
	 * @param Meta $meta The Meta value object.
	 *
	 * @throws MetadataNotWritableException If we can't save the metadata.
	 *
	 * @return void
	 */
	public function write( Meta $meta ): void;

	/**
	 * Delete the metadata from the location it was written.
	 *
	 * @param Meta $meta The Meta value object.
	 *
	 * @return void
	 */
	public function delete( Meta $meta ): void;
}
