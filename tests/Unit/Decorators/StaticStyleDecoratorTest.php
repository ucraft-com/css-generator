<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Decorators;

use CssGenerator\Decorators\StaticStyleDecorator;
use PHPUnit\Framework\TestCase;

class StaticStyleDecoratorTest extends TestCase
{
    public function testToString_WhenGivenNecessaryData_ReturnsStaticStyleBlock(): void
    {
        $styles = [
            'font-size'   => '10px',
            'padding-top' => '5px',
        ];

        $staticStyleDecorator = new StaticStyleDecorator();
        $staticStyleDecorator->setStyles($styles);
        $staticStyleDecorator->setSelector('h1');

        $expected = 'h1 {font-size: 10px;padding-top: 5px;}';
        $this->assertEquals($expected, (string)$staticStyleDecorator);
    }
}
