<?php
/**
 * Handles managing cache files.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Page_Cache;

use InvalidArgumentException;
use RuntimeException;
use SolidWP\Performance\Http\Request;
use SolidWP\Performance\Page_Cache\Compression\Collection;
use SolidWP\Performance\Page_Cache\Compression\Contracts\Compressible;
use SolidWP\Performance\Page_Cache\Compression\Exceptions\CompressionFailedException;
use SolidWP\Performance\Page_Cache\Meta\Exceptions\MetadataNotWritableException;
use SolidWP\Performance\Page_Cache\Meta\Meta_Manager;

/**
 * Handles managing cache files.
 *
 * @package SolidWP\Performance
 */
final class Cache_Handler {

	/**
	 * @var Compressible
	 */
	private Compressible $compressor;

	/**
	 * @var Request
	 */
	private Request $request;

	/**
	 * @var Debug
	 */
	private Debug $debug;

	/**
	 * @var Expiration
	 */
	private Expiration $expiration;

	/**
	 * @var Cache_Path
	 */
	private Cache_Path $cache_path;

	/**
	 * @var Meta_Manager
	 */
	private Meta_Manager $meta_manager;

	/**
	 * @param Collection   $compressors The collection of compression algorithms.
	 * @param Request      $request The current request.
	 * @param Debug        $debug The debugger.
	 * @param Expiration   $expiration The expiration manager.
	 * @param Cache_Path   $cache_path The server path to the cache directory.
	 * @param Meta_Manager $meta_manager The meta manager.
	 */
	public function __construct(
		Collection $compressors,
		Request $request,
		Debug $debug,
		Expiration $expiration,
		Cache_Path $cache_path,
		Meta_Manager $meta_manager
	) {
		$this->compressor   = $compressors->get_by_header( (string) $request->header->get( 'accept-encoding' ) );
		$this->request      = $request;
		$this->debug        = $debug;
		$this->expiration   = $expiration;
		$this->cache_path   = $cache_path;
		$this->meta_manager = $meta_manager;
	}

	/**
	 * Save a file to the cache based on the type of compression the browser is asking for
	 * and what the server supports.
	 *
	 * @param string $output Output to save in the cached file.
	 *
	 * @throws RuntimeException If the cache directory or cache file can't be created.
	 * @throws InvalidArgumentException If the URL is empty.
	 * @throws MetadataNotWritableException If we can't write the metadata.
	 *
	 * @return void
	 */
	public function save( string $output ): void {
		$this->ensure_cache_dir_exists();

		$file = $this->get_file_path();

		// Append HTML debug comment, if enabled.
		$output .= $this->debug->get_debug_comment( $this->request, $this->compressor );

		// Append HTML generated by comment if debugging is DISABLED.
		$output .= $this->debug->get_generated_by_comment();

		// Don't save any fails that failed to compress their content.
		try {
			$compressed = $this->compressor->compress( $output );
		} catch ( CompressionFailedException $e ) {
			swpsp_log( sprintf( 'Failed to compress content using "%s" compression: %s', $this->compressor->encoding(), $e->getMessage() ) );

			return;
		}

		$result = swpsp_direct_filesystem()->put_contents( $file, $compressed, swpsp_get_file_mode() );

		if ( $result === false ) {
			throw new RuntimeException( sprintf( 'File could not be saved to cache: %s', $file ) );
		}

		// Save the cache metadata.
		$this->meta_manager->save( $this->request->uri );
	}

	/**
	 * Converts a URL into a directory structure.
	 *
	 * All cached requests will be saved to a directory structure that matches
	 * the relative URL. This provides a way to consistently get the cached
	 * resource from the URL.
	 *
	 * @throws RuntimeException When URL does not have valid host.
	 *
	 * @return string
	 */
	public function get_file_path(): string {
		$path = $this->cache_path->get_path_from_url( $this->request->uri );
		$ext  = $this->compressor->extension();

		return "$path.$ext";
	}

	/**
	 * Whether the current cache file exists and is not expired.
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return file_exists( $this->get_file_path() ) && ! $this->expiration->file_expired( $this->get_file_path() );
	}

	/**
	 * Get the current compression strategy.
	 *
	 * @return Compressible
	 */
	public function compressor(): Compressible {
		return $this->compressor;
	}

	/**
	 * Make sure the complete directory we'll be saving our file to, actually exists.
	 *
	 * @throws RuntimeException If the cache directory can't be created.
	 *
	 * @return void
	 */
	private function ensure_cache_dir_exists(): void {
		$dir = dirname( $this->get_file_path() );

		// If the file's directory structure does not exist yet, create it.
		if ( ! is_dir( $dir ) ) {
			$result = wp_mkdir_p( $dir );

			if ( ! $result ) {
				throw new RuntimeException( 'Unable to make directory: ' . $dir );
			}
		}
	}
}
