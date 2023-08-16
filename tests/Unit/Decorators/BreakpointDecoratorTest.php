<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Decorators;

use CssGenerator\Decorators\BreakpointDecorator;
use CssGenerator\Decorators\StyleDecorator;
use PHPUnit\Framework\TestCase;

class BreakpointDecoratorTest extends TestCase
{
    public function testToString_WhenBreakpointIsNotDefault_ReturnsCssBlocksWrappedByBreakpoints(): void
    {
        $styleDecorator = new StyleDecorator();
        $styleDecorator->setSelector('[data-widget-hash="random-hash"]');
        $styleDecorator->setStyles([
            [
                'type'  => 'font-size',
                'value' => '10px'
            ]
        ]);

        $breakpointDecorator = new BreakpointDecorator();
        $breakpointDecorator->setIsDefault(false);
        $breakpointDecorator->setId(1);
        $breakpointDecorator->setMediaQuery('@media (max-width: 768px) {');
        $breakpointDecorator->addStyle('[data-widget-hash="random-hash"]', $styleDecorator);

        $expected = '@media (max-width: 768px) {[data-widget-hash="random-hash"] {font-size: 10px;}}';
        $this->assertEquals($expected, (string)$breakpointDecorator);
    }

    public function testToString_WhenBreakpointIsDefault_ReturnsCssBlocksNotWrapped(): void
    {
        $styleDecorator = new StyleDecorator();
        $styleDecorator->setSelector('[data-widget-hash="random-hash"]');
        $styleDecorator->setStyles([
            [
                'type'  => 'font-size',
                'value' => '10px'
            ]
        ]);

        $breakpointDecorator = new BreakpointDecorator();
        $breakpointDecorator->setIsDefault(true);
        $breakpointDecorator->setId(1);
        $breakpointDecorator->setMediaQuery('@media (max-width: 768px) {'); // ignores this line
        $breakpointDecorator->addStyle('[data-widget-hash="random-hash"]', $styleDecorator);

        $expected = '[data-widget-hash="random-hash"] {font-size: 10px;}';
        $this->assertEquals($expected, (string)$breakpointDecorator);
    }
}
