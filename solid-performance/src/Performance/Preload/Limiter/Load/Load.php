<?php
/**
 * The Current System Load Average DTO.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

namespace SolidWP\Performance\Preload\Limiter\Load;

/**
 * The Current System Load Average DTO.
 *
 * @package SolidWP\Performance
 */
final class Load {

	/**
	 * The one-minute load average.
	 *
	 * @var float
	 */
	private float $one_min;

	/**
	 * The five-minute load average.
	 *
	 * @var float
	 */
	private float $five_min;

	/**
	 * The fifteen-minute load average.
	 *
	 * @var float
	 */
	private float $fifteen_min;

	/**
	 * Create a new load instance.
	 *
	 * @param float[] $load The system load averages.
	 *
	 * @return self
	 */
	public static function from( array $load ): self {
		return new self( ...$load );
	}

	/**
	 * @param float $one_min The one-minute load average.
	 * @param float $five_min The five-minute load average.
	 * @param float $fifteen_min The fifteen-minute load average.
	 */
	private function __construct( float $one_min, float $five_min, float $fifteen_min ) {
		$this->one_min     = $one_min;
		$this->five_min    = $five_min;
		$this->fifteen_min = $fifteen_min;
	}

	/**
	 * Get all the system load averages.
	 *
	 * @return array<string, float>
	 */
	public function all(): array {
		return [
			'1'  => $this->one_min,
			'5'  => $this->five_min,
			'15' => $this->fifteen_min,
		];
	}

	/**
	 * Get the one-minute load average.
	 *
	 * @return float
	 */
	public function one_min(): float {
		return $this->one_min;
	}

	/**
	 * Get the five-minute load average.
	 *
	 * @return float
	 */
	public function five_min(): float {
		return $this->five_min;
	}

	/**
	 * Get the fifteen-minute load average.
	 *
	 * @return float
	 */
	public function fifteen_min(): float {
		return $this->fifteen_min;
	}
}
