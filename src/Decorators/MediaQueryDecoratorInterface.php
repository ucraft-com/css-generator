<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

interface MediaQueryDecoratorInterface extends StyleDecoratorInterface
{
    /**
     * Apply styles data, and generate css string, wrapped by media query.
     *
     * @param array  $styles
     * @param string $mediaQuery
     *
     * @return string
     */
    public function applyStyle(array $styles, string $mediaQuery = ''): string;
}
