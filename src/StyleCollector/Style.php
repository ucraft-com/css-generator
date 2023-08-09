<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use CssGenerator\StrategyFactory;

use function rtrim;

class Style
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
        $transformCss = 'transform: ';

        // start css block
        $css .= !empty($this->styles) ? "{$this->selector} {".PHP_EOL : '';

        foreach ($this->styles as $style) {
            $type = $style['type'];
            $value = $style['value'];

            // 'transform' case
            if (isset($style['group']) && $style['group'] === 'transform') {
                $transformCss .= "$type($value) "; // join transform styles separated by spaces
                continue;
            }

            $css .= StrategyFactory::create($style['type'])->convert($style, $this->mediaMapping);
        }

        if ($transformCss !== 'transform: ') {
            $css .= rtrim($transformCss).';'.PHP_EOL; // add collected transform property, value
        }

        // close css block
        $css .= !empty($this->styles) ? '}'.PHP_EOL : '';

        return $css;
    }
}
