<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class StaticStyle implements StyleDecoratorInterface
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
        return '';
    }
}
