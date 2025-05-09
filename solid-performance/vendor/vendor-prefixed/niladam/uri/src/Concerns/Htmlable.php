<?php

namespace SolidWP\Performance\Niladam\Uri\Concerns;

interface Htmlable
{
    /**
     * Convert the object to its HTML string representation.
     *
     * @return string
     */
    public function toHtml(): string;
}
