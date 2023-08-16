<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

/**
 * Stylesheet contains breakpoints, and generates css.
 */
class Stylesheet implements StyleDecoratorInterface
{
    /**
     * @var array<\CssGenerator\Decorators\BreakpointDecorator>
     */
    protected array $breakpoints = [];

    /**
     * @param array $breakpoints
     *
     * @return void
     */
    public function setBreakpoints(array $breakpoints): void
    {
        $this->breakpoints = $breakpoints;
    }

    /**
     * Generate final css, based on breakpoints.
     *
     * @return string
     */
    public function __toString(): string
    {
        return implode('', $this->breakpoints);
    }
}
