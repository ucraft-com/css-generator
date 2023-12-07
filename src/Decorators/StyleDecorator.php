<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

use CssGenerator\StrategyFactory;

use function filter_var;

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

        // 'text-shadow' property name
        $textShadowProperty = 'text-shadow:';
        $textShadowCss = [];

        // start css block
        $css .= !empty($this->styles) ? "{$this->selector} {" : '';

        foreach ($this->styles as $style) {
            if (!isset($style['value']) || $style['value'] === '') {
                continue;
            }

            $type = $style['type'];
            $value = $style['value'];

            // 'transform' case
            if (isset($style['group']) && $style['group'] === 'transform') {
                $transformCss[] = "$type($value)"; // collect transform styles
                continue;
            }

            // 'text-shadow' case
            if (isset($style['group']) && $style['group'] === 'text-shadow') {
                $textShadowCss[$type] = $value; // collect text-shadow styles
                continue;
            }

            $css .= StrategyFactory::create($style['type'])->convert($style, $this->mediaMapping);
        }

        if (!empty($transformCss)) {
            $css .= $transformProperty.join(' ', $transformCss).';'; // add collected transform property, value
        }

        if (!empty($textShadowCss) && !empty($textShadowCss['text-shadow-enabled']) && filter_var(
                $textShadowCss['text-shadow-enabled'],
                FILTER_VALIDATE_BOOLEAN
            ) === true) {
            $css .= $textShadowProperty.' '.$textShadowCss['text-shadow-offset-x'].' '.$textShadowCss['text-shadow-offset-y'].' '.($textShadowCss['text-shadow-blur-radius'] ?? 0).' '.$textShadowCss['text-shadow-color'].';';
        }

        // close css block
        $css .= !empty($this->styles) ? '}' : '';

        return $css;
    }
}
