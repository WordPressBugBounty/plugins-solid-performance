<?php
/**
 * The API provided by each builder.
 *
 * @package lucatume\DI52
 */

namespace SolidWP\Performance\lucatume\DI52\Builders;

/**
 * Interface BuilderInterface
 *
 * @package SolidWP\Performance\lucatume\DI52\Builders
 */
interface BuilderInterface
{
    /**
     * Builds and returns the implementation handled by the builder.
     *
     * @return mixed The implementation provided by the builder.
     */
    public function build();
}
