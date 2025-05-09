<?php
/**
 * A meta writer decorator that sanitizes the metadata before the
 * underlying meta writer saves it.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Writers;

use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Writer;
use SolidWP\Performance\Page_Cache\Meta\Meta;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Collection;

/**
 * A meta writer decorator that sanitizes the metadata before the
 * underlying meta writer saves it.
 *
 * @package SolidWP\Performance
 */
final class Sanitized_Meta_Writer implements Meta_Writer {

	/**
	 * @var Meta_Writer
	 */
	private Meta_Writer $writer;

	/**
	 * @var Collection
	 */
	private Collection $sanitizers;

	/**
	 * @param Meta_Writer $writer The original meta writer.
	 * @param Collection  $sanitizers The collection of sanitizers to run the data through.
	 */
	public function __construct( Meta_Writer $writer, Collection $sanitizers ) {
		$this->writer     = $writer;
		$this->sanitizers = $sanitizers;
	}

	/**
	 * Sanitizes the metadata and then passes it to the underlying meta writer.
	 *
	 * @param Meta $meta The Meta value object.
	 *
	 * @return void
	 */
	public function write( Meta $meta ): void {
		$this->writer->write( $this->sanitizers->sanitize( $meta ) );
	}

	/**
	 * Delete the stored metadata.
	 *
	 * @param Meta $meta The Meta value object.
	 *
	 * @return void
	 */
	public function delete( Meta $meta ): void {
		$this->writer->delete( $meta );
	}
}
