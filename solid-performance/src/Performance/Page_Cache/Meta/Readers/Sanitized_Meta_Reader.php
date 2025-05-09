<?php
/**
 * A meta reader decorator that sanitizes the metadata after reading it from
 * the underlying meta reader.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Readers;

use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Reader;
use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotReadableException;
use SolidWP\Performance\Page_Cache\Meta\Meta;
use SolidWP\Performance\Page_Cache\Meta\Sanitization\Collection;

/**
 * A meta reader decorator that sanitizes the metadata after reading it from
 * the underlying meta reader.
 *
 * @package SolidWP\Performance
 */
final class Sanitized_Meta_Reader implements Meta_Reader {

	/**
	 * @var Meta_Reader
	 */
	private Meta_Reader $reader;

	/**
	 * @var Collection
	 */
	private Collection $sanitizers;

	/**
	 * @param Meta_Reader $reader The original meta reader.
	 * @param Collection  $sanitizers The collection of sanitizers to run the data through.
	 */
	public function __construct( Meta_Reader $reader, Collection $sanitizers ) {
		$this->reader     = $reader;
		$this->sanitizers = $sanitizers;
	}

	/**
	 * Read metadata from the underlying meta reader and return a sanitized version of it.
	 *
	 * @param string $url The URL associated with the metadata.
	 *
	 * @throws MetadataNotReadableException If we cannot find the metadata.
	 *
	 * @return Meta
	 */
	public function read( string $url ): Meta {
		$meta = $this->reader->read( $url );

		return $this->sanitizers->sanitize( $meta );
	}
}
