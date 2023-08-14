<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class MediaQueryQueryDecorator extends AbstractStyleDecorator
{
    protected string $mediaQuery = '';

    /**
     * Apply styles data, and generate css string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $css = parent::applyStyle($styles);
        $eol = PHP_EOL;

        return "$mediaQuery $eol $css} $eol";
    }
}
