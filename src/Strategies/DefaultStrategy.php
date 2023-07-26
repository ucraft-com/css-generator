<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

/**
 * DefaultStrategy combines css property with value.
 */
class DefaultStrategy implements StrategyInterface
{
    /**
     * Converts given variantsStyles to relative css string.
     *
     * @param array $variantStyle
     *
     * @return string
     */
    public function convert(array $variantStyle): string
    {
        $type = $variantStyle['type'];
        $value = $variantStyle['value'];

        return "$type: $value;".PHP_EOL;
    }
}
