<?php

namespace CssGenerator\Strategies;

interface StrategyInterfaceWithMediaMapping extends StrategyInterface
{
    /**
     * Converts given variantsStyles to relative css string.
     *
     * @param array $variantStyle
     * @param array $mediaMapping
     *
     * @return string
     */
    public function convert(array $variantStyle, array $mediaMapping = []): string;
}
