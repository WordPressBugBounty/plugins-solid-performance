<?php
/**
 * Manages reading and writing cache meta.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta;

use InvalidArgumentException;
use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Reader;
use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Writer;
use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotWritableException;
use Throwable;

/**
 * Manages reading and writing cache meta.
 *
 * @package SolidWP\Performance
 */
final class Meta_Manager {

	/**
	 * @var Meta_Reader
	 */
	private Meta_Reader $reader;

	/**
	 * @var Meta_Writer
	 */
	private Meta_Writer $writer;

	/**
	 * @var Meta_Factory
	 */
	private Meta_Factory $factory;

	/**
	 * @param Meta_Reader  $reader The Meta reader.
	 * @param Meta_Writer  $writer The Meta writer.
	 * @param Meta_Factory $factory The Meta factory.
	 */
	public function __construct( Meta_Reader $reader, Meta_Writer $writer, Meta_Factory $factory ) {
		$this->reader  = $reader;
		$this->writer  = $writer;
		$this->factory = $factory;
	}

	/**
	 * Read the stored metadata from the reader.
	 *
	 * @param string $url The URL associated with the meta.
	 *
	 * @return Meta|null
	 */
	public function read( string $url ): ?Meta {
		try {
			return $this->reader->read( $url );
		} catch ( Throwable $e ) {
			return null;
		}
	}

	/**
	 * Capture the Meta for a URL and save it.
	 *
	 * @param string $url The URL associated with the meta.
	 *
	 * @throws MetadataNotWritableException If we can't write the metadata.
	 * @throws InvalidArgumentException If the $url is empty.
	 *
	 * @return void
	 */
	public function save( string $url ): void {
		$meta = $this->factory->make( $url );

		$this->writer->write( $meta );
	}

	/**
	 * Delete the current Meta's storage data.
	 *
	 * @param string $url The URL associated with the meta.
	 *
	 * @throws InvalidArgumentException If the $url is empty.
	 *
	 * @return void
	 */
	public function delete( string $url ): void {
		$meta = $this->factory->make( $url );

		$this->writer->delete( $meta );
	}
}
