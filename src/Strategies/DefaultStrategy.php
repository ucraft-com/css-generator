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
        if (!isset($value['value']) || $value['value'] === '') {
            return '';
        }

        $type = $variantStyle['type'];
        $value = $variantStyle['value'];

        return "$type: $value;";
    }
}
