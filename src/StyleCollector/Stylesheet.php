<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

/**
 * Stylesheet contains breakpoints, and generates css.
 */
class Stylesheet
{
    /**
     * @var array<Breakpoint>
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
        $style = '';
        foreach ($this->breakpoints as $breakpoint) {
            if (!$breakpoint->isDefault() && !empty((string)$breakpoint)) {
                $style .= $breakpoint->getMediaQuery(); // open @media
                $style .= $breakpoint;
                $style .= '}'.PHP_EOL; // close @media
            } else {
                $style .= $breakpoint;
            }
        }

        return $style;
    }
}
