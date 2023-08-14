<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class StaticStyleDecorator extends AbstractStyleDecorator
{
    protected array $styles = [];

    protected string $selector;

    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    /**
     * Apply styles data, and generate css string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $css = "{$this->selector} {".PHP_EOL;
        foreach ($this->styles as $property => $value) {
            $css .= "$property: $value;".PHP_EOL;
        }
        $css .= '}'.PHP_EOL;

        return $css;
    }
}
