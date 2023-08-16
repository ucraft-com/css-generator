<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Decorators;

use CssGenerator\Decorators\BreakpointDecorator;
use CssGenerator\Decorators\StyleDecorator;
use CssGenerator\Decorators\Stylesheet;
use PHPUnit\Framework\TestCase;

class StylesheetTest extends TestCase
{
    public function testToString_WhenGivenBreakpoints_ReturnsCssBlockWrappedByBreakpoints(): void
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

        $breakpointDecorator2 = new BreakpointDecorator();
        $breakpointDecorator2->setIsDefault(false);
        $breakpointDecorator2->setId(2);
        $breakpointDecorator2->setMediaQuery('@media (max-width: 1280) {');
        $breakpointDecorator2->addStyle('[data-widget-hash="random-hash"]', $styleDecorator);

        $breakpointDecorator2 = new BreakpointDecorator();
        $breakpointDecorator2->setIsDefault(true);
        $breakpointDecorator2->setId(3);
        $breakpointDecorator2->setMediaQuery('@media (max-width: 1920) {');
        $breakpointDecorator2->addStyle('[data-widget-hash="random-hash"]', $styleDecorator);

        $stylesheet = new Stylesheet();
        $stylesheet->setBreakpoints([$breakpointDecorator, $breakpointDecorator2]);

        $expected = '@media (max-width: 768px) {[data-widget-hash="random-hash"] {font-size: 10px;}}[data-widget-hash="random-hash"] {font-size: 10px;}';
        $this->assertEquals($expected, (string)$stylesheet);
    }
}
