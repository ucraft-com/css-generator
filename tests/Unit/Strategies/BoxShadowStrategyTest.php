<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Strategies;

use CssGenerator\Strategies\BoxShadowStrategy;
use PHPUnit\Framework\TestCase;

class BoxShadowStrategyTest extends TestCase
{
    public function testConvert_WhenGivenBoxShadow_ReturnsBoxShadowString(): void
    {
        $styles = [
            "type"  => "box-shadow",
            "value" => [
                "offset-x"      => "0px",
                "offset-y"      => "0px",
                "blur-radius"   => "0px",
                "spread-radius" => "0px",
                "color"         => "rgba(0, 0, 0, 0.1)",
                "active"        => true
            ]
        ];

        $boxShadowStrategy = new BoxShadowStrategy();
        $css = $boxShadowStrategy->convert($styles);

        $expected = 'box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.1);';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenBoxShadowIsNotActive_ReturnsBoxShadowUnset(): void
    {
        $styles = [
            "type"  => "box-shadow",
            "value" => [
                "offset-x"      => "0px",
                "offset-y"      => "0px",
                "blur-radius"   => "0px",
                "spread-radius" => "0px",
                "color"         => "rgba(0, 0, 0, 0.1)",
                "active"        => false
            ]
        ];

        $boxShadowStrategy = new BoxShadowStrategy();
        $css = $boxShadowStrategy->convert($styles);

        $expected = 'box-shadow: unset;';
        $this->assertEquals($expected, $css);
    }
}
