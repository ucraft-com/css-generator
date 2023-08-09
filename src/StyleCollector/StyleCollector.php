<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use function array_unshift;

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
            'fonts'          => [],
            'colors'          => [],
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
            $newBreakpoint->setWidth($breakpoint['width']);
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

    public function build(): void
    {
        $colors = [
                            ":root[data-theme='light']" => [
                                '--color-1' => 'rgb(0, 0, 0)'
                            ]
        ];

        /**
         * @var string $widgetId
         * @var array  $variantsStyle
         */
        foreach ($this->data['variantsStyles'] as $widgetId => $variantsStyle) {
            $selector = $this->generateSelector($widgetId, $variantsStyle);

            foreach ($variantsStyle as $item) {
                $itemBreakpointId = (int)$item['breakpointId'];

                /** @var Breakpoint $breakpoint */
                $breakpoint = $this->data['breakpoints'][$itemBreakpointId];

                $style = new Style();
                $style->setSelector($selector);
                $style->setRules($item);
                $style->setMediaMapping($this->getMedia());
                $breakpoint->addStyle($widgetId, $style);
            }
        }
        
        foreach ($colors as $color){
            
        }

        /** @var Stylesheet $stylesheet */
        $stylesheet = $this->data['stylesheet'];
        $stylesheet->setBreakpoints($this->data['breakpoints']);
    }

    protected function getMedia(): array
    {
        return $this->data['media'];
    }

    protected function generateSelector(string $widgetId, array $variantsStyle): string
    {
        if (str_contains($widgetId, 'uiElement')) {
            $selector = ".$widgetId";
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
