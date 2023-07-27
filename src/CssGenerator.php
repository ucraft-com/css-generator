<?php

declare(strict_types=1);

namespace CssGenerator;

/**
 * CssGenerator converts variantsStyles array into css string.
 */
class CssGenerator
{
    /**
     * Convert variantsStyles to css string.
     *
     * @param array $variantsStyles
     * @param array $breakpointMapping
     * @param array $mediaMapping
     *
     * @return string
     */
    public function generate(array $variantsStyles, array $breakpointMapping, array $mediaMapping): string
    {
        // Group by breakpoint id
        $groupedByBreakpoint = $this->groupByBreakpoint($variantsStyles, $breakpointMapping);

        // Order by based on already sorted breakpoints (low -> high)
        $orderedBreakpoints = [];
        if (!empty($groupedByBreakpoint['others'])) {
            foreach ($breakpointMapping['breakpoints'] as $breakpointId => $resolution) {
                $orderedBreakpoints[$breakpointId] = $groupedByBreakpoint['others'][$breakpointId];
            }
        }

        $defaultBreakpoint = $groupedByBreakpoint['default'] ?? [];

        $css = '';

        // Loop through all breakpoints including default
        foreach ($defaultBreakpoint + $orderedBreakpoints as $breakpointId => $variantsStyle) {
            $isDefault = $breakpointMapping['defaultBreakpointId'] === $breakpointId;
            $resolution = $breakpointMapping['breakpoints'][$breakpointId] ?? null; // in case of default it is null

            // no need @media for default breakpoint
            if (!$isDefault) {
                // start @media query block
                $css .= "@media (max-width: {$resolution}px) {".PHP_EOL;
            }

            foreach ($variantsStyle as $widgetHash => $item) {
                foreach ($item as $variantsStyleItem) {
                    // transform property name
                    $transformCss = 'transform: ';
                    $selector = $this->generateSelector($widgetHash, $variantsStyleItem);

                    // start css block
                    $css .= !empty($variantsStyleItem['styles']) ? "$selector {".PHP_EOL : '';

                    foreach ($variantsStyleItem['styles'] as $style) {
                        $type = $style['type'];
                        $value = $style['value'];

                        // transform case
                        if (isset($style['group']) && $style['group'] === 'transform') {
                            $transformCss .= "$type($value) "; // join transform styles separated by spaces
                            continue;
                        }

                        $css .= StrategyFactory::create($style['type'])->convert($style, $mediaMapping);
                    }

                    if ($transformCss !== 'transform: ') {
                        $css .= rtrim($transformCss).';'.PHP_EOL; // add collected transform property, value
                    }

                    // close css block
                    $css .= !empty($variantsStyleItem['styles']) ? '}'.PHP_EOL : '';
                }
            }

            // no need @media for default breakpoint
            if (!$isDefault) {
                // close @media query block
                $css .= '}'.PHP_EOL;
            }
        }

        return $css;
    }

    /**
     * Generate css selector name.
     *
     * @param string $widgetHash
     * @param array  $variantsStyle
     *
     * @return string
     */
    protected function generateSelector(string $widgetHash, array $variantsStyle): string
    {
        $selector = "[data-widget-hash=\"$widgetHash\"]";

        if (!empty($variantsStyle['widgetState'])) {
            $selector .= '.'.$variantsStyle['widgetState'];
        }

        if (!empty($variantsStyle['cssState']) && $variantsStyle['cssState'] !== 'normal') {
            $selector .= ':'.$variantsStyle['cssState'];
        }

        return $selector;
    }

    /**
     * Group variants styles by breakpoint id.
     *
     * @param array $variantsStyles
     * @param array $breakpointMapping
     *
     * @return array
     */
    protected function groupByBreakpoint(array $variantsStyles, array $breakpointMapping): array
    {
        // Group by breakpoint id
        $groupedByBreakpoint = [];
        foreach ($variantsStyles as $widgetHash => $variantsStyle) {
            foreach ($variantsStyle as $item) {
                $itemBreakpointId = (int)$item['breakpointId'];

                if ($itemBreakpointId === $breakpointMapping['defaultBreakpointId']) {
                    $groupedByBreakpoint['default'][$itemBreakpointId][$widgetHash][] = $item;
                } else {
                    $groupedByBreakpoint['others'][$itemBreakpointId][$widgetHash][] = $item;
                }
            }
        }

        return $groupedByBreakpoint;
    }
}
