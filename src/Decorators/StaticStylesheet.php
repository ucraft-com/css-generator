<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

/**
 * StaticStylesheet contains style without breakpoints, and generates css.
 */
class StaticStylesheet implements StylesheetInterface
{
    protected string $colorMediaQuery;

    /**
     * @var array<\CssGenerator\Decorators\StaticStyleDecorator>
     */
    protected array $styles = [];

    /**
     * @var int
     */
    protected int $breakpointId;

    /**
     * @param int $breakpointId
     *
     * @return void
     */
    public function setBreakpointId(int $breakpointId): void
    {
        $this->breakpointId = $breakpointId;
    }

    /**
     * @return int
     */
    public function getBreakpointId(): int
    {
        return $this->breakpointId ?? 0;
    }

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
     * @return array
     */
    public function generate(): array
    {
        $css = '';

        if (!empty($this->colorMediaQuery)) {
            $css .= $this->colorMediaQuery;
        }

        $css .= implode('', $this->styles);

        if (!empty($this->colorMediaQuery)) {
            $css .= '}';
        }

        return [$this->getBreakpointId() => $css];
    }
}
