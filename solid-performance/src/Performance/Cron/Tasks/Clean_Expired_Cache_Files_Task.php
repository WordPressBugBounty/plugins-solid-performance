<?php
/**
 * A scheduled task to deleted expired cache files.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Cron\Tasks;

use SolidWP\Performance\Cron\Contracts\Scheduled_Task;
use SolidWP\Performance\Cron\Provider;
use SolidWP\Performance\Cron\Schedule;
use SolidWP\Performance\Page_Cache\Purge\Expired_Cache_Purger;
use SolidWP\Performance\Psr\Log\LoggerInterface;

/**
 * A scheduled task to deleted expired cache files.
 *
 * @package SolidWP\Performance
 */
final class Clean_Expired_Cache_Files_Task extends Scheduled_Task {

	/**
	 * @var Expired_Cache_Purger
	 */
	private Expired_Cache_Purger $expired_cache_purger;

	/**
	 *
	 * @see Provider::register_schedules()
	 *
	 * @var Schedule
	 */
	private Schedule $schedule;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * @param Expired_Cache_Purger $expired_cache_purger The expired cached purger.
	 * @param Schedule             $schedule The custom schedule to use.
	 * @param LoggerInterface      $logger The logger.
	 */
	public function __construct(
		Expired_Cache_Purger $expired_cache_purger,
		Schedule $schedule,
		LoggerInterface $logger
	) {
		$this->expired_cache_purger = $expired_cache_purger;
		$this->schedule             = $schedule;
		$this->logger               = $logger;
	}

	/**
	 * Run the scheduled task.
	 *
	 * @return int
	 */
	public function run(): int {
		$this->logger->debug(
			'Executing scheduled task: {name}',
			[
				'name' => self::class,
			]
		);

		$count = $this->expired_cache_purger->purge();

		$this->logger->debug(
			'Expired Cache Purger purged {count} files',
			[
				'count' => $count,
			]
		);

		return $count;
	}

	/**
	 * @inheritDoc
	 */
	public function recurrence(): string {
		return $this->schedule->name();
	}
}
