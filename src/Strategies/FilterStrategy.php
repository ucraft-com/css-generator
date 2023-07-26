<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

class FilterStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    protected string $key = 'filter: ';

    /**
     * Converts given variantsStyles to relative css string.
     *
     * @param array $variantStyle
     *
     * @return string
     */
    public function convert(array $variantStyle): string
    {
        foreach ($variantStyle['value'] as $value) {
            $filterType = $value['type'];
            $filterValue = $value['value'];

            if ($filterType !== 'dropShadow') {
                $this->key .= "$filterType($filterValue) ";
            } else {
                $this->key .= 'drop-shadow(';
                foreach ($filterValue as $property) {
                    $this->key .= $property.' ';
                }
                $this->key .= ')';
            }
        }

        if ($this->key !== 'filter: ') {
            return rtrim($this->key).';'.PHP_EOL;
        }

        return '';
    }
}
