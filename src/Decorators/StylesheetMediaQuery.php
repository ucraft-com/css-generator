<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

/**
 * StylesheetMediaQuery contains breakpoints, and generates css with media queries.
 */
class StylesheetMediaQuery implements StylesheetInterface
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
     * @return array
     */
    public function generate(): array
    {
        return ['default breakpoint id' => implode('', $this->breakpoints)];
    }
}
