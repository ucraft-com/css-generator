<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Strategies;

use CssGenerator\Strategies\DefaultStrategy;
use PHPUnit\Framework\TestCase;

class DefaultStrategyTest extends TestCase
{
    public function testConvert_SimpleCase_ReturnsPropertyValue(): void
    {
        $variantsStyles = [
            [
                'type'  => 'padding-top',
                'value' => '0px'
            ],
            [
                'type'  => 'font-size',
                'value' => '22px'
            ],
            [
                'type'  => 'border-style',
                'value' => 'solid'
            ],
        ];
        $assertion = [
            'padding-top: 0px;',
            'font-size: 22px;',
            'border-style: solid;',
        ];

        $defaultStrategy = new DefaultStrategy();
        foreach ($variantsStyles as $index => $variantsStyle) {
            $css = $defaultStrategy->convert($variantsStyle);
            $this->assertEquals($assertion[$index], $css);
        }
    }

    public function testConvert_WhenGivenFontFamily_ReturnsValueWrappedWithQuote(): void
    {
        $variantsStyles = [
            'type'  => 'font-family',
            'value' => "Something Fam'ily"
        ];

        $defaultStrategy = new DefaultStrategy();

        $css = $defaultStrategy->convert($variantsStyles);

        $this->assertEquals('font-family: "Something Fam\'ily";', $css);
    }
}
