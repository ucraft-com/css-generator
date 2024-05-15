<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use function implode;

class BreakpointDecorator implements StyleDecoratorInterface
{
    /**
     * @var array|StyleDecorator[]
     */
    protected array $styles = [];

    /**
     * @param string                                  $widgetId
     * @param \CssGenerator\Decorators\StyleDecorator $style
     *
     * @return void
     */
    public function addStyle(string $widgetId, StyleDecorator $style): void
    {
        $this->styles[$widgetId][] = $style;
    }

    /**
     * Bring together already generated css blocks.
     *
     * @return string
     */
    public function __toString(): string
    {
        $css = '';

        foreach ($this->styles as $styles) {
            $css .= implode('', $styles);
        }

        return $css;
    }
}
