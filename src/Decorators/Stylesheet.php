<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

/**
 * Stylesheet contains breakpoints, and generates css.
 */
class Stylesheet implements StylesheetInterface
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
        $result = [];

        /**
         * @var int                                          $breakpointId
         * @var \CssGenerator\Decorators\BreakpointDecorator $breakpoint
         */
        foreach ($this->breakpoints as $breakpointId => $breakpoint) {
            $result[$breakpointId] = (string)$breakpoint;
        }

        return $result;
    }
}
