<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

interface StylesheetInterface extends StyleInterface
{
    /**
     * @return array
     */
    public function generate(): array;
}
