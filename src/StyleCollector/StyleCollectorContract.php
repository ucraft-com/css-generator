<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

interface StyleCollectorContract
{
    public function getStylesheet(): Stylesheet;
}
