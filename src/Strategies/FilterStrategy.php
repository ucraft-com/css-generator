<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

class FilterStrategy implements StrategyInterface
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
        $property = '';

        foreach ($variantStyle['value'] as $value) {
            $filterType = $value['type'];
            $filterValue = $value['value'];

            if ($filterType === 'dropShadow') {
                $filterType = 'drop-shadow';
                $filterValue = join(' ', $filterValue);
            }

            $property .= " $filterType($filterValue)";
        }

        return !empty($property) ? 'filter:'.$property.';' : '';
    }
}
