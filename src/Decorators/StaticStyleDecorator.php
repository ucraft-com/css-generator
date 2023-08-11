<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class StaticStyleDecorator extends AbstractStyleDecorator
{
    /**
     * Apply styles data, and generate css string.
     *
     * @param array $styles
     *
     * @return string
     */
    public function applyStyle(array $styles): string
    {
        $css = parent::applyStyle($styles);

        foreach ($styles as $style) {
            $css .= $style['selector'].' {'.PHP_EOL;
            foreach ($style['styles'] as $property => $value) {
                $css .= "$property: $value;".PHP_EOL;
            }
            $css .= '}'.PHP_EOL;
        }

        return $css;
    }
}
