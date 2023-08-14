<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

/**
 * StaticStylesheet contains style without breakpoints, and generates css.
 */
class StaticStylesheet extends AbstractStyleDecorator
{
    /**
     * @var array<\CssGenerator\Decorators\StaticStyleDecorator>
     */
    protected array $styles = [];

    /**
     * @param array $styles
     *
     * @return void
     */
    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    /**
     * Generate final css, for simple styles.
     *
     * @return string
     */
    public function __toString(): string
    {
        return implode('', $this->styles);
    }
}
