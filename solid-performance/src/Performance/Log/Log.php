<?php
/**
 * Log data using WordPress actions.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Log;

/**
 * Log data using WordPress actions.
 *
 * @example do_action( Log::WARNING, 'Something is wrong!', [ 'additional data' ] );
 */
final class Log {

	public const EMERGENCY = 'solidwp/log/emergency';
	public const ALERT     = 'solidwp/log/alert';
	public const CRITICAL  = 'solidwp/log/critical';
	public const ERROR     = 'solidwp/log/error';
	public const WARNING   = 'solidwp/log/warning';
	public const NOTICE    = 'solidwp/log/notice';
	public const INFO      = 'solidwp/log/info';
	public const DEBUG     = 'solidwp/log/debug';
}
