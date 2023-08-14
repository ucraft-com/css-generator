<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

class StaticStyleDecorator extends AbstractStyleDecorator
{
    /**
     * @var array
     */
    protected array $styles = [];

    /**
     * @var string
     */
    protected string $selector;

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
     * @param string $selector
     *
     * @return void
     */
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
