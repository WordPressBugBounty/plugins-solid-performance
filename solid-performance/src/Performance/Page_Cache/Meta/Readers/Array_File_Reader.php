<?php
/**
 * Reads from a PHP file that contains an array of metadata.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache\Meta\Readers;

use SolidWP\Performance\Page_Cache\Meta\Contracts\Meta_Reader;
use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotReadableException;
use SolidWP\Performance\Page_Cache\Meta\Meta;
use SolidWP\Performance\Page_Cache\Meta\Meta_File;

/**
 * Reads from a PHP file that contains an array of metadata.
 *
 * @package SolidWP\Performance
 */
final class Array_File_Reader implements Meta_Reader {

	/**
	 * @var Meta_File
	 */
	private Meta_File $meta_file;

	/**
	 * @param Meta_File $meta_file The meta file.
	 */
	public function __construct( Meta_File $meta_file ) {
		$this->meta_file = $meta_file;
	}

	/**
	 * Read metadata from a file and return a populated Meta value object.
	 *
	 * @param string $url The URL associated with the metadata.
	 *
	 * @throws MetadataNotReadableException If we cannot find the metadata file.
	 *
	 * @return Meta
	 */
	public function read( string $url ): Meta {
		$file_path = $this->meta_file->get_path_from_url( $url );

		if ( ! is_readable( $file_path ) ) {
			throw new MetadataNotReadableException( "File not found: $file_path" );
		}

		$meta = include $file_path;

		return Meta::from( $meta );
	}
}
