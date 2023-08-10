<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

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
            'breakpoints'    => [],
            'media'          => [],
            'variantsStyles' => [],
            'stylesheet'     => new Stylesheet(),
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
            $newBreakpoint = new Breakpoint();
            $newBreakpoint->setIsDefault($breakpoint['default']);
            $newBreakpoint->setId($breakpoint['id']);

            if (!$breakpoint['default']) {
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
     * @return \CssGenerator\StyleCollector\Stylesheet
     */
    public function getStylesheet(): Stylesheet
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
     * Convert data to Style data structures.
     *
     * @return void
     */
    public function build(): void
    {
        /**
         * @var string $widgetId
         * @var array  $variantsStyle
         */
        foreach ($this->data['variantsStyles'] as $widgetId => $variantsStyle) {
            $selector = $this->generateSelector($widgetId, $variantsStyle);

            foreach ($variantsStyle as $item) {
                // todo tmp solution, until '[DEFAULT_BREAKPOINT_ID]' will be removed
                if ($item['breakpointId'] === '[DEFAULT_BREAKPOINT_ID]') {
                    $itemBreakpointId = 3;
                } else {
                    $itemBreakpointId = (int)$item['breakpointId'];
                }

                $style = new Style();
                $style->setSelector($selector);
                $style->setStyles($item['styles']);
                $style->setMediaMapping($this->data['media']);

                /** @var Breakpoint $breakpoint */
                $breakpoint = $this->data['breakpoints'][$itemBreakpointId];
                $breakpoint->addStyle($widgetId, $style);
            }
        }

        /** @var Stylesheet $stylesheet */
        $stylesheet = $this->data['stylesheet'];
        $stylesheet->setBreakpoints($this->data['breakpoints']);
    }

    /**
     * Generate selector for css block.
     *
     * @param string $widgetId
     * @param array  $variantsStyle
     *
     * @return string
     */
    protected function generateSelector(string $widgetId, array $variantsStyle): string
    {
        if (str_contains($widgetId, 'uiElement')) {
            $selector = ".$widgetId";
        } elseif (str_contains($widgetId, '[class~="page"]')) {
            $selector = $widgetId;
        } else {
            $selector = "[data-widget-hash=\"$widgetId\"]";
        }

        if (!empty($variantsStyle['widgetState'])) {
            $selector .= '.'.$variantsStyle['widgetState'];
        }

        if (!empty($variantsStyle['cssState']) && $variantsStyle['cssState'] !== 'normal') {
            $selector .= ':'.$variantsStyle['cssState'];
        }

        return $selector;
    }
}
