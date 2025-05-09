<?php
/**
 * The swpsp-nginx.conf manager.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cache_Delivery\Nginx;

use SolidWP\Performance\Cache_Delivery\Contracts\Readable;
use SolidWP\Performance\Cache_Delivery\Contracts\Writable;
use SolidWP\Performance\Cache_Delivery\Exceptions\CacheDeliveryReadException;
use SolidWP\Performance\Symfony\Component\Filesystem\Exception\IOException;
use SolidWP\Performance\Symfony\Component\Filesystem\Filesystem;
use SolidWP\Performance\View\Exceptions\FileNotFoundException;

/**
 * The swpsp-nginx.conf manager.
 *
 * @package SolidWP\Performance
 */
final class Manager {

	/**
	 * @var Readable
	 */
	private Readable $reader;

	/**
	 * @var Writable
	 */
	private Writable $writer;

	/**
	 * @var Nginx_Conf_File
	 */
	private Nginx_Conf_File $nginx_conf;

	/**
	 * @var Filesystem
	 */
	private Filesystem $filesystem;

	/**
	 * @var Template_Loader
	 */
	private Template_Loader $template_loader;

	/**
	 * @var Bypass_Template_Loader
	 */
	private Bypass_Template_Loader $bypass_template_loader;

	/**
	 * @param Readable               $reader The config file reader.
	 * @param Writable               $writer The config file writer.
	 * @param Nginx_Conf_File        $nginx_conf The nginx.conf object.
	 * @param Filesystem             $filesystem The filesystem component.
	 * @param Template_Loader        $template_loader The template loader.
	 * @param Bypass_Template_Loader $bypass_template_loader The cache bypass template loader.
	 */
	public function __construct(
		Readable $reader,
		Writable $writer,
		Nginx_Conf_File $nginx_conf,
		Filesystem $filesystem,
		Template_Loader $template_loader,
		Bypass_Template_Loader $bypass_template_loader
	) {
		$this->reader                 = $reader;
		$this->writer                 = $writer;
		$this->nginx_conf             = $nginx_conf;
		$this->filesystem             = $filesystem;
		$this->template_loader        = $template_loader;
		$this->bypass_template_loader = $bypass_template_loader;
	}

	/**
	 * If this feature is supported by the current server environment.
	 *
	 * @return bool
	 */
	public function supported(): bool {
		// Allow Solid Security define override.
		if ( defined( 'ITSEC_SERVER_OVERRIDE' ) ) {
			return ITSEC_SERVER_OVERRIDE === 'nginx';
		}

		global $is_nginx;

		return (bool) $is_nginx;
	}

	/**
	 * Check if the swpsp-nginx.conf exists.
	 *
	 * @throws \RuntimeException If we can't find the document root.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		return $this->nginx_conf->exists();
	}

	/**
	 * Get the path to the swpsp-nginx.conf file.
	 *
	 * @throws \RuntimeException If we can't find the document root.
	 *
	 * @return string
	 */
	public function path(): string {
		return $this->nginx_conf->get_file_path();
	}

	/**
	 * Create or update the swpsp-nginx.conf.
	 *
	 * @throws \RuntimeException If we can't find the document root.
	 * @throws FileNotFoundException If the template loader can't find the view file.
	 *
	 * @return bool
	 */
	public function add(): bool {
		return $this->writer->write( $this->template_loader->get() );
	}

	/**
	 * Get the current rules.
	 *
	 * @throws CacheDeliveryReadException If we are unable to open or lock the swpsp-nginx.conf file for
	 *     reading.
	 *
	 * @return string
	 */
	public function get(): string {
		return $this->reader->read();
	}

	/**
	 * Write swpsp-nginx.conf rules to bypass caching.
	 *
	 * @throws FileNotFoundException If the template loader can't find the view file.
	 *
	 * @return bool
	 */
	public function bypass(): bool {
		return $this->writer->write( $this->bypass_template_loader->get() );
	}

	/**
	 * Whether our rules have populated swpsp-nginx.conf.
	 *
	 * @return bool
	 */
	public function has_rules(): bool {
		try {
			$rules = $this->get();

			return str_contains(
				$rules,
				'BEGIN SolidPerformanceNginxCacheRules'
			);
		} catch ( CacheDeliveryReadException $e ) {
			return false;
		}
	}

	/**
	 * Delete the swpsp-nginx.conf file.
	 *
	 * @throws IOException If we fail to remove the swpsp-nginx.conf.
	 * @throws \RuntimeException If we can't find the document root.
	 *
	 * @return void
	 */
	public function remove(): void {
		$path = $this->nginx_conf->get_file_path();

		$this->filesystem->remove( $path );
	}
}
