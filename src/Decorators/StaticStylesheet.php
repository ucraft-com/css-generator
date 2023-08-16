<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

/**
 * StaticStylesheet contains style without breakpoints, and generates css.
 */
class StaticStylesheet extends AbstractStyleDecorator
{
    protected string $colorMediaQuery;

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
     * @param string $colorMediaQuery
     *
     * @return void
     */
    public function setColorMedaQuery(string $colorMediaQuery): void
    {
        $this->colorMediaQuery = $colorMediaQuery;
    }

    /**
     * Generate final css, for simple styles.
     *
     * @return string
     */
    public function __toString(): string
    {
        $css = '';

        if (!empty($this->colorMediaQuery)) {
            $css .= $this->colorMediaQuery;
        }

        $css .= implode('', $this->styles);

        if (!empty($this->colorMediaQuery)) {
            $css .= '}';
        }

        return $css;
    }
}
