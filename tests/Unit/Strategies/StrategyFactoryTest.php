<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Strategies;

use CssGenerator\Strategies\BackgroundStrategy;
use CssGenerator\Strategies\BoxShadowStrategy;
use CssGenerator\Strategies\DefaultStrategy;
use CssGenerator\Strategies\FilterStrategy;
use CssGenerator\StrategyFactory;
use PHPUnit\Framework\TestCase;

class StrategyFactoryTest extends TestCase
{
    public function testCreate_WhenGivenFilterAsArgument_ReturnsFilterStrategyInstance(): void
    {
        $expected = new FilterStrategy();
        $actual = StrategyFactory::create('filter');

        $this->assertEquals($expected, $actual);
    }

    public function testCreate_WhenGivenBackgroundAsArgument_ReturnsBackgroundStrategyInstance(): void
    {
        $expected = new BackgroundStrategy();
        $actual = StrategyFactory::create('background');

        $this->assertEquals($expected, $actual);
    }

    public function testCreate_WhenGivenBoxShadowAsArgument_ReturnsBoxShadowInstance(): void
    {
        $expected = new BoxShadowStrategy();
        $actual = StrategyFactory::create('box-shadow');

        $this->assertEquals($expected, $actual);
    }

    public function testCreate_WhenGivenOtherValueAsArgument_ReturnsDefaultStrategyInstance(): void
    {
        $expected = new DefaultStrategy();
        $actual = StrategyFactory::create('font-size');

        $this->assertEquals($expected, $actual);
    }
}
