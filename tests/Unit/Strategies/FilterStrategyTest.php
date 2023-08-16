<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Strategies;

use CssGenerator\Strategies\FilterStrategy;
use PHPUnit\Framework\TestCase;

class FilterStrategyTest extends TestCase
{
    public function testConvert_WhenGivenFilterDropShadow_ReturnsDropShadowFilterString(): void
    {
        $styles = [
            "type"  => "filter",
            "value" => [
                [
                    "type"  => "dropShadow",
                    "value" => [
                        "drop-shadow-offset-x"    => "0px",
                        "drop-shadow-offset-y"    => "0px",
                        "drop-shadow-blur-radius" => "0px",
                        "drop-shadow-color"       => "rgba(0, 0, 0, 0.1)"
                    ]
                ]
            ]
        ];

        $filterStrategy = new FilterStrategy();
        $css = $filterStrategy->convert($styles);

        $expected = 'filter: drop-shadow(0px 0px 0px rgba(0, 0, 0, 0.1));';
        $this->assertEquals($expected, $css);
    }
    public function testConvert_WhenGivenSimpleFilter_ReturnsSimpleFilterString(): void
    {
        $styles = [
            "type"  => "filter",
            "value" => [
                [
                    "type"  => "opacity",
                    "value" => '45%'
                ]
            ]
        ];

        $filterStrategy = new FilterStrategy();
        $css = $filterStrategy->convert($styles);

        $expected = 'filter: opacity(45%);';
        $this->assertEquals($expected, $css);
    }
    public function testConvert_WhenGivenMultipleFilters_ReturnsFilterStringWithMultipleValues(): void
    {
        $styles = [
            "type" => "filter",
            "value" => [
                [
                    "type" => "opacity",
                    "value" => "27%"
                ],
                [
                    "type" => "dropShadow",
                    "value" => [
                        "drop-shadow-offset-x" => "12px",
                        "drop-shadow-offset-y" => "12px",
                        "drop-shadow-blur-radius" => "12px",
                        "drop-shadow-color" => "rgb(0,0,0)"
                    ]
                ],
                [
                    "type" => "blur",
                    "value" => "25px"
                ],
                [
                    "type" => "brightness",
                    "value" => "100%"
                ]
            ]
        ];

        $filterStrategy = new FilterStrategy();
        $css = $filterStrategy->convert($styles);

        $expected = 'filter: opacity(27%) drop-shadow(12px 12px 12px rgb(0,0,0)) blur(25px) brightness(100%);';
        $this->assertEquals($expected, $css);
    }
}
