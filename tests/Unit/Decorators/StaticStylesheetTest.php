<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Decorators;

use CssGenerator\Decorators\StaticStyleDecorator;
use CssGenerator\Decorators\StaticStylesheet;
use PHPUnit\Framework\TestCase;

class StaticStylesheetTest extends TestCase
{
    public function testToString_WhenGivenStaticStyles_ReturnsCssBlocks(): void
    {
        $styles = [
            'font-size'   => '10px',
            'padding-top' => '5px',
        ];

        $staticStyleDecorator = new StaticStyleDecorator();
        $staticStyleDecorator->setStyles($styles);
        $staticStyleDecorator->setSelector('h1');

        $styles2 = [
            'margin-top'  => '6px',
            'line-height' => '7px',
        ];

        $staticStyleDecorator2 = new StaticStyleDecorator();
        $staticStyleDecorator2->setStyles($styles2);
        $staticStyleDecorator2->setSelector('h2');

        $staticStylesheet = new StaticStylesheet();
        $staticStylesheet->setStyles([$staticStyleDecorator, $staticStyleDecorator2]);

        $expected = 'h1 {font-size: 10px;padding-top: 5px;}h2 {margin-top: 6px;line-height: 7px;}';

        $this->assertEquals($expected, (string)$staticStylesheet);
    }

    public function testToString_WhenGivenStaticStylesAndColorQuery_ReturnsCssBlocksWrappedWithQuery(): void
    {
        $styles = [
            'font-size'   => '10px',
            'padding-top' => '5px',
        ];

        $staticStyleDecorator = new StaticStyleDecorator();
        $staticStyleDecorator->setStyles($styles);
        $staticStyleDecorator->setSelector('html[data-theme="dark"]:root');

        $styles2 = [
            'margin-top'  => '6px',
            'line-height' => '7px',
        ];

        $staticStyleDecorator2 = new StaticStyleDecorator();
        $staticStyleDecorator2->setStyles($styles2);
        $staticStyleDecorator2->setSelector('html[data-theme="light"]:root');

        $staticStylesheet = new StaticStylesheet();
        $staticStylesheet->setStyles([$staticStyleDecorator, $staticStyleDecorator2]);
        $staticStylesheet->setColorMedaQuery('@media (prefers-color-scheme: dark) {');

        $expected = '@media (prefers-color-scheme: dark) {html[data-theme="dark"]:root {font-size: 10px;padding-top: 5px;}html[data-theme="light"]:root {margin-top: 6px;line-height: 7px;}}';

        $this->assertEquals($expected, (string)$staticStylesheet);
    }
}
