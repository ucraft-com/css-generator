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
            ]
        ]);

        $expected = '[data-widget-hash="random-hash"] {font-size: 10px;}';
        $this->assertEquals($expected, (string)$styleDecorator);
    }
}
