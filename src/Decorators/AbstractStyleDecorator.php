<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class AbstractStyleDecorator implements StyleDecoratorInterface
{
    public function __construct(protected StyleDecoratorInterface $style)
    {
    }

    /**
     * Apply styles data, and generate css string.
     *
     * @param array $styles
     *
     * @return string
     */
    public function applyStyle(array $styles): string
    {
        return $this->style->applyStyle($styles);
    }
}
