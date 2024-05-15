<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use CssGenerator\Decorators\StyleInterface;

interface StyleCollectorContract
{
    /**
     * @return \CssGenerator\Decorators\StyleInterface
     */
    public function getStylesheet(): StyleInterface;
}
