<?php
/**
 * The route to manage generating and removing our nginx.conf rules.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\API\Routes\Page_Cache;

use SolidWP\Performance\API\Base_Route;
use SolidWP\Performance\Cache_Delivery\Nginx\Manager;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The route to manage generating and removing our nginx.conf rules.
 *
 * @package SolidWP\Performance
 */
final class Nginx extends Base_Route {

	public const CODE_SUCCESS = 'solid_performance_nginx_success';
	public const CODE_FAILED  = 'solid_performance_nginx_failed';

	/**
	 * @var Manager
	 */
	private Manager $manager;

	/**
	 * @param Manager $manager The Nginx manager.
	 */
	public function __construct( Manager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path(): string {
		return '/page/nginx';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_methods() {
		return [
			WP_REST_Server::READABLE,
			WP_REST_Server::CREATABLE,
			WP_REST_Server::DELETABLE,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function callback( WP_REST_Request $request ) {
		if ( $request->get_method() === WP_REST_Server::DELETABLE ) {
			$result = $this->manager->bypass();

			if ( ! $result ) {
				return new WP_Error(
					self::CODE_FAILED,
					__( 'Failed to add nginx rules bypass', 'solid-performance' ),
				);
			}

			return new WP_REST_Response(
				[
					'code'    => self::CODE_SUCCESS,
					'message' => __( 'Nginx bypass rules added. Your Nginx server must be reloaded in order for the changes to take effect.', 'solid-performance' ),
				]
			);
		} elseif ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			$result = $this->manager->add();

			if ( ! $result ) {
				return new WP_Error(
					self::CODE_FAILED,
					__( 'Failed to regenerate nginx.conf rules', 'solid-performance' ),
				);
			}

			return new WP_REST_Response(
				[
					'code'    => self::CODE_SUCCESS,
					'message' => __( 'Nginx rules regenerated. Your Nginx server must be reloaded in order for the changes to take effect.', 'solid-performance' ),
				]
			);
		}

		return new WP_REST_Response(
			[
				'code'      => self::CODE_SUCCESS,
				'has_rules' => $this->manager->has_rules(),
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function schema_callback(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'setting',
			'type'       => 'object',
			'properties' => [
				'code'      => [
					'description' => esc_html__( 'The identification code of the Nginx action.', 'solid-performance' ),
					'type'        => 'string',
					'enum'        => [
						self::CODE_SUCCESS,
						self::CODE_FAILED,
					],
					'readonly'    => true,
				],
				'message'   => [
					'description' => esc_html__( 'The formatted message of the Nginx action.', 'solid-performance' ),
					'type'        => 'string',
					'readonly'    => true,
				],
				'has_rules' => [
					'description' => esc_html__( 'Whether our rules are present in the nginx.conf file.', 'solid-performance' ),
					'type'        => 'boolean',
					'readonly'    => true,
					'required'    => false,
				],
			],
		];
	}
}
