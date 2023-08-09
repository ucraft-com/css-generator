<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

class Stylesheet
{
    protected array $breakpoints = [];

    public function setBreakpoints(array $breakpoints): void
    {
        $this->breakpoints = $breakpoints;
    }

    public function __toString(): string
    {
        $style = '';
        /** @var Breakpoint $breakpoint */
        foreach ($this->breakpoints as $breakpoint) {
            if(!$breakpoint->isDefault() && !empty((string)$breakpoint)){
                $style .= $breakpoint->getMediaQuery(); // open @media
                $style .= $breakpoint;
                $style .= '}'.PHP_EOL; // close @media
            }else{
                $style .= $breakpoint;
            }
        }

        return $style;
    }
}
