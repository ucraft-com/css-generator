<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit;

use CssGenerator\CssGenerator;
use CssGenerator\StyleCollector\StyleCollector;
use CssGenerator\StyleCollector\StyleCollectorContract;
use PHPUnit\Framework\TestCase;

class CssGeneratorTest extends TestCase
{
    public function testGenerate_WhenGivenSimpleStyles_GenerateWithoutBreakpoints(): void
    {
        $styleCollector = $this->getStyleCollectorInstance();
        $variantsStyles = [
            [
                'selector' => 'p',
                'styles'   => [
                    'text-transform' => 'var(--text-text-transform)',
                    'color'          => 'var(--text-color)',
                ]
            ],
            [
                'selector' => 'h1',
                'styles'   => [
                    'font-size'   => 'var(--h1-font-size)',
                    'line-height' => 'var(--h1-line-height)',
                ]
            ],
        ];

        $styleCollector
            ->assignVariantsStyles($variantsStyles)
            ->build();

        $generator = new CssGenerator($styleCollector);
        $css = $generator->generate();

        $expected = 'p {text-transform: var(--text-text-transform);color: var(--text-color);}h1 {font-size: var(--h1-font-size);line-height: var(--h1-line-height);}';

        $this->assertEquals($expected, $css);
    }

    public function testGenerate_WhenGivenWithBreakpoints_GeneratesBasedOnBreakpoints(): void
    {
        $breakpoints = [
            [
                "id"        => 1,
                "width"     => 320,
                "default"   => false,
                "selected"  => false,
                "createdAt" => "2022-05-19T11:57:40.000000Z",
            ],
            [
                "id"        => 2,
                "width"     => 769,
                "default"   => false,
                "selected"  => false,
                "createdAt" => "2022-05-19T11:57:40.000000Z",
            ],
            [
                "id"        => 3,
                "width"     => 1281,
                "default"   => true,
                "selected"  => true,
                "createdAt" => "2022-05-19T11:57:40.000000Z",
            ],
            [
                "id"        => 4,
                "width"     => 1441,
                "default"   => false,
                "selected"  => false,
                "createdAt" => "2022-05-19T11:57:40.000000Z",
            ],
            [
                "id"        => 5,
                "width"     => 1921,
                "default"   => false,
                "selected"  => false,
                "createdAt" => "2022-05-19T11:57:40.000000Z",
            ],
        ];

        $variantsStyles = [
            '[data-widget-hash="random-hash"]' => [
                [
                    'styles'       => [
                        [
                            "type"  => "font-family",
                            "value" => "Helvetica"
                        ]
                    ],
                    'cssState'     => 'normal',
                    'breakpointId' => 3
                ],
                [
                    'styles'       => [
                        [
                            "type"  => "color",
                            "value" => "rgb(0, 0, 0)"
                        ]
                    ],
                    'cssState'     => 'hover',
                    'breakpointId' => 1
                ]
            ]
        ];

        $styleCollector = $this->getStyleCollectorInstance();
        $styleCollector
            ->assignBreakpoints($breakpoints)
            ->assignVariantsStyles($variantsStyles)
            ->build();

        $generator = new CssGenerator($styleCollector);
        $css = $generator->generate();

        $expected = '[data-widget-hash="random-hash"] {font-family: Helvetica;}@media (max-width: 1280px) {}@media (max-width: 768px) {[data-widget-hash="random-hash"]:hover {color: rgb(0, 0, 0);}}@media (min-width: 1441px) {}@media (min-width: 1921px) {}';

        $this->assertEquals($expected, $css);
    }

    protected function getStyleCollectorInstance(): StyleCollectorContract
    {
        return new StyleCollector();
    }
}
