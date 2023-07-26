<?php

declare(strict_types=1);

namespace CssGenerator;

use CssGenerator\Strategies\BackgroundStrategyWithMediaMapping;
use CssGenerator\Strategies\DefaultStrategy;
use CssGenerator\Strategies\FilterStrategy;
use CssGenerator\Strategies\StrategyInterface;

/**
 * StrategyFactory creates Strategies based on css type.
 */
class StrategyFactory
{
    /**
     * @param string $type
     *
     * @return \CssGenerator\Strategies\StrategyInterface
     */
    public static function create(string $type): StrategyInterface
    {
        return match ($type) {
            "filter" => new FilterStrategy(),
            "background" => new BackgroundStrategyWithMediaMapping(),
            default => new DefaultStrategy(),
        };
    }
}
