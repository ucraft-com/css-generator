<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use CssGenerator\Decorators\BreakpointDecorator;
use CssGenerator\Decorators\StaticStyleDecorator;
use CssGenerator\Decorators\StaticStylesheet;
use CssGenerator\Decorators\StyleDecorator;
use CssGenerator\Decorators\StyleDecoratorInterface;
use CssGenerator\Decorators\Stylesheet;

use function array_unshift;

/**
 * StyleCollector collects all necessary data for generating css.
 */
class StyleCollector implements StyleCollectorContract
{
    /**
     * @var array
     */
    protected array $data;

    public function __construct()
    {
        $this->data = [
            'breakpoints'     => [],
            'media'           => [],
            'variantsStyles'  => [],
            'colorMediaQuery' => '',
        ];
    }

    /**
     * @param array    $media
     * @param callable $pathResolver
     *
     * @return $this
     */
    public function assignMedia(array $media, callable $pathResolver): StyleCollector
    {
        $formattedMedia = [];
        foreach ($media as $medium) {
            $formattedMedia[$medium['id']] = $pathResolver($medium['filename']);
        }

        $this->data['media'] = $formattedMedia;

        return $this;
    }

    /**
     * @param array $breakpoints
     *
     * @return $this
     */
    public function assignBreakpoints(array $breakpoints): StyleCollector
    {
        $defaultBreakpointWidth = 0;
        $sortedBreakpoints = [];

        // Sort breakpoints, if breakpoint is less than default, must be reverse sorted
        // example: [320, 769, 1281, 1441, 1921] -> [1281, 769, 320, 1441, 1921] (1281 is default)
        foreach ($breakpoints as $breakpoint) {
            $newBreakpoint = new BreakpointDecorator();
            $newBreakpoint->setIsDefault($breakpoint['default']);
            $newBreakpoint->setId($breakpoint['id']);

            if (!$newBreakpoint->isDefault()) {
                if ($defaultBreakpointWidth === 0 || $defaultBreakpointWidth > $breakpoint['width']) {
                    $newBreakpoint->setMediaQuery("@media (max-width: {$breakpoint['width']}px) {".PHP_EOL);
                    array_unshift($sortedBreakpoints, $newBreakpoint);
                } else {
                    $newBreakpoint->setMediaQuery("@media (min-width: {$breakpoint['width']}px) {".PHP_EOL);
                    $sortedBreakpoints[] = $newBreakpoint;
                }
            }

            if ($breakpoint['default']) {
                $defaultBreakpointWidth = $breakpoint['width'];
                array_unshift($sortedBreakpoints, $newBreakpoint);
            }
        }

        foreach ($sortedBreakpoints as $sortedBreakpoint) {
            $this->data['breakpoints'][$sortedBreakpoint->getId()] = $sortedBreakpoint;
        }

        return $this;
    }

    /**
     * @return \CssGenerator\Decorators\StyleDecoratorInterface
     */
    public function getStylesheet(): StyleDecoratorInterface
    {
        return $this->data['stylesheet'];
    }

    /**
     * @param array $variantsStyles
     *
     * @return $this
     */
    public function assignVariantsStyles(array $variantsStyles): StyleCollector
    {
        $this->data['variantsStyles'] = $variantsStyles;

        return $this;
    }

    /**
     * @param string $colorMediaQuery
     *
     * @return $this
     */
    public function assignColorMediaQuery(string $colorMediaQuery): StyleCollector
    {
        $this->data['colorMediaQuery'] = $colorMediaQuery;

        return $this;
    }

    /**
     * Convert data to Style data structures.
     *
     * @return void
     */
    public function build(): void
    {
        // For simple or general styles we dont have breakpoints
        if (empty($this->data['breakpoints'])) {
            $this->buildWithoutBreakpoint();
            return;
        }

        /**
         * @var string $selector
         * @var array  $variantsStyle
         */
        foreach ($this->data['variantsStyles'] as $selector => $variantsStyle) {
            foreach ($variantsStyle as $item) {
                $selector = $this->generateSelector($selector, $item);
                // todo tmp solution, until '[DEFAULT_BREAKPOINT_ID]' will be removed
                if ($item['breakpointId'] === '[DEFAULT_BREAKPOINT_ID]') {
                    $itemBreakpointId = 3;
                } else {
                    $itemBreakpointId = (int)$item['breakpointId'];
                }

                $style = new StyleDecorator();
                $style->setSelector($selector);
                $style->setStyles($item['styles']);
                $style->setMediaMapping($this->data['media']);

                /** @var BreakpointDecorator $breakpoint */
                $breakpoint = $this->data['breakpoints'][$itemBreakpointId];
                $breakpoint->addStyle($selector, $style);
            }
        }

        $stylesheet = new Stylesheet();
        $stylesheet->setBreakpoints($this->data['breakpoints']);
        $this->data['stylesheet'] = $stylesheet;
    }

    /**
     * Convert data to Style data structures, without breakpoints.
     *
     * @return void
     */
    protected function buildWithoutBreakpoint(): void
    {
        $styles = [];

        /**
         * @var string $selector
         * @var array  $variantsStyle
         */
        foreach ($this->data['variantsStyles'] as $variantsStyle) {
            $style = new StaticStyleDecorator();
            $style->setSelector($variantsStyle['selector']);
            $style->setStyles($variantsStyle['styles']);
            $styles[] = $style;
        }

        $stylesheet = new StaticStylesheet();
        $stylesheet->setStyles($styles);
        $stylesheet->setColorMedaQuery($this->data['colorMediaQuery']);
        $this->data['stylesheet'] = $stylesheet;
    }

    /**
     * Generate selector for css block.
     *
     * @param string $selector
     * @param array  $variantsStyle
     *
     * @return string
     */
    protected function generateSelector(string $selector, array $variantsStyle): string
    {
        if (!empty($variantsStyle['widgetState'])) {
            $selector .= '.'.$variantsStyle['widgetState'];
        }

        if (!empty($variantsStyle['cssState']) && $variantsStyle['cssState'] !== 'normal') {
            $selector .= ':'.$variantsStyle['cssState'];
        }

        return $selector;
    }
}
