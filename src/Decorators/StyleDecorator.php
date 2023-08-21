<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use CssGenerator\StrategyFactory;

use function rtrim;

class StyleDecorator implements StyleDecoratorInterface
{
    /**
     * @var string Css selector
     */
    protected string $selector;

    /**
     * @var array Styles that must be converted to string
     */
    protected array $styles = [];

    /**
     * @var array Media files mapping: [1 => 'path/to/media.jpg']
     */
    protected array $mediaMapping = [];

    /**
     * @param array $mediaMapping
     *
     * @return void
     */
    public function setMediaMapping(array $mediaMapping): void
    {
        $this->mediaMapping = $mediaMapping;
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
     * @param array $styles
     *
     * @return void
     */
    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    /**
     * Generate css blocks, based on styles.
     *
     * @return string
     */
    public function __toString(): string
    {
        $css = '';

        // 'transform' property name
        $transformProperty = 'transform:';
        $transformCss = [];

        // start css block
        $css .= !empty($this->styles) ? "{$this->selector} {" : '';

        foreach ($this->styles as $style) {
            if (!isset($value['value']) || $value['value'] === '') {
                continue;
            }

            $type = $style['type'];
            $value = $style['value'];

            // 'transform' case
            if (isset($style['group']) && $style['group'] === 'transform') {
                $transformCss[] = "$type($value)"; // collect transform styles
                continue;
            }

            $css .= StrategyFactory::create($style['type'])->convert($style, $this->mediaMapping);
        }

        if (!empty($transformCss)) {
            $css .= $transformProperty.join(' ', $transformCss).';'; // add collected transform property, value
        }

        // close css block
        $css .= !empty($this->styles) ? '}' : '';

        return $css;
    }
}
