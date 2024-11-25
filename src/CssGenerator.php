<?php

declare(strict_types=1);

namespace CssGenerator;

use CssGenerator\StyleCollector\StyleCollectorContract;

use function join;

/**
 * CssGenerator converts variantsStyles array into css string.
 */
class CssGenerator
{
    public function __construct(protected StyleCollectorContract $styleCollector)
    {

    }

    /**
     * Convert variantsStyles to array of css string key by breakpoint id.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->styleCollector->getStylesheet()->generate();
    }

    /**
     * Convert variantsStyles to css string.
     *
     * @return string
     */
    public function generateStylesheet(): string
    {
        return join($this->styleCollector->getStylesheet()->generate());
    }
}
