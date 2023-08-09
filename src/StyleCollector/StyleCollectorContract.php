<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

interface StyleCollectorContract
{
    /**
     * @return \CssGenerator\StyleCollector\Stylesheet
     */
    public function getStylesheet(): Stylesheet;
}
