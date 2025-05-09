<?php

declare(strict_types=1);

namespace SolidWP\Performance\StellarWP\Memoize\Drivers;

use SolidWP\Performance\StellarWP\Memoize\Contracts\DriverInterface;
use SolidWP\Performance\StellarWP\Memoize\Traits\MemoizeTrait;

final class MemoryDriver implements DriverInterface
{
    use MemoizeTrait;
}
