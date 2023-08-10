<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class MediaQueryQueryDecorator extends AbstractStyleDecorator implements MediaQueryDecoratorInterface
{
    /**
     * Apply styles data, and generate css string.
     *
     * @param array  $styles
     * @param string $mediaQuery
     *
     * @return string
     */
    public function applyStyle(array $styles, string $mediaQuery = ''): string
    {
        $css = parent::applyStyle($styles);
        $eol = PHP_EOL;

        return "$mediaQuery $eol $css} $eol";
    }
}
