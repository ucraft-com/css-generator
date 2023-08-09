<?php

declare(strict_types=1);

namespace CssGenerator;

use CssGenerator\StyleCollector\StyleCollectorContract;

/**
 * CssGenerator converts variantsStyles array into css string.
 */
class CssGenerator
{
    public function __construct(protected StyleCollectorContract $styleCollector)
    {

    }

    /**
     * Convert variantsStyles to css string.
     *
     * @return string
     */
    public function generate(): string
    {
        return (string)$this->styleCollector->getStylesheet();
    }
}
