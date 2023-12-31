<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

use function is_string;

/**
 * BoxShadowStrategy handles box-shadow styles.
 */
class BoxShadowStrategy implements StrategyInterface
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
        if (empty($variantStyle['value']) || is_string($variantStyle['value'])) {
            return '';
        }

        $property = 'box-shadow:';
        $value = $variantStyle['value'];

        if (!$value['active']) {
            return $property.' unset;';
        }

        if (!empty($value['offset-x'])) {
            $property .= ' '.$value['offset-x'];
        }

        if (!empty($value['offset-y'])) {
            $property .= ' '.$value['offset-y'];
        }

        if (!empty($value['blur-radius'])) {
            $property .= ' '.$value['blur-radius'];
        }

        if (!empty($value['spread-radius'])) {
            $property .= ' '.$value['spread-radius'];
        }

        if (!empty($value['color'])) {
            $property .= ' '.$value['color'];
        }

        return $property.';';
    }
}
