<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use CssGenerator\Decorators\StyleDecoratorInterface;

interface StyleCollectorContract
{
    /**
     * @return \CssGenerator\Decorators\StyleDecoratorInterface
     */
    public function getStylesheet(): StyleDecoratorInterface;
}
