<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

interface StrategyInterface
{
    /**
     * Converts given variantsStyles to relative css string.
     *
     * @param array $variantStyle
     *
     * @return string
     */
    public function convert(array $variantStyle): string;
}
