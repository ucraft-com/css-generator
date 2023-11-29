<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Decorators;

use CssGenerator\Decorators\StyleDecorator;
use PHPUnit\Framework\TestCase;

class StyleDecoratorTest extends TestCase
{
    public function testToString_WhenGivenSimpleStyles_ReturnsCssBlock(): void
    {
        $styleDecorator = new StyleDecorator();
        $styleDecorator->setSelector('[data-widget-hash="random-hash"]');
        $styleDecorator->setStyles([
            [
                'type'  => 'font-size',
                'value' => '10px'
            ],
            [
                "type"  => "text-shadow",
                "value" => "var(--h2-text-shadow-offset-x)\n        var(--h2-text-shadow-offset-y) \n        var(--h2-text-shadow-blur-radius) \n        var(--h2-text-shadow-color);"
            ],
            [
                "type"  => "text-shadow-enabled",
                "group" => "text-shadow",
                "value" => "true"
            ],
            [
                "type"  => "text-shadow-color",
                "group" => "text-shadow",
                "value" => "rgba(255, 0, 0, 1)"
            ],
            [
                "type"  => "text-shadow-offset-x",
                "group" => "text-shadow",
                "value" => "7px"
            ],
            [
                "type"  => "text-shadow-offset-y",
                "group" => "text-shadow",
                "value" => "8px"
            ],
            [
                "type"  => "text-shadow-blur-radius",
                "group" => "text-shadow",
                "value" => "3px"
            ]
        ]);

        $expected = '[data-widget-hash="random-hash"] {font-size: 10px;text-shadow: var(--h2-text-shadow-offset-x)
        var(--h2-text-shadow-offset-y) 
        var(--h2-text-shadow-blur-radius) 
        var(--h2-text-shadow-color);;text-shadow: 7px 8px 3px rgba(255, 0, 0, 1);}';
        $this->assertEquals($expected, (string)$styleDecorator);
    }
}
