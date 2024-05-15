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
        $css = $generator->generate()[0];

        $expected = 'p {text-transform: var(--text-text-transform);color: var(--text-color);}h1 {font-size: var(--h1-font-size);line-height: var(--h1-line-height);}';

        $this->assertEquals($expected, $css);
    }

    public function testGenerate_WhenGivenSimpleStylesAndBackground_GenerateCorrectBackgroundWithWebP(): void
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

        $styleCollector = $this->getStyleCollectorInstance();

        $variantsStyles = [
            '[data-widget-hash="random-hash"]' => [
                [
                    'styles'       => [
                        [
                            "type"  => "background",
                            "value" => [
                                [
                                    "type"   => "image",
                                    "value"  => [
                                        "data" => [
                                            "backgroundSize"       => "cover",
                                            "backgroundPosition"   => "50% 50%",
                                            "backgroundRepeat"     => "no-repeat",
                                            "backgroundAttachment" => "scroll",
                                            "mediaId"              => "1"
                                        ]
                                    ],
                                    "active" => true
                                ]
                            ]
                        ]
                    ],
                    'cssState'     => 'normal',
                    'breakpointId' => 3
                ],
                [
                    'styles'       => [
                        [
                            "type"  => "background",
                            "value" => [
                                [
                                    "type"   => "image",
                                    "value"  => [
                                        "data" => [
                                            "backgroundSize"       => "cover",
                                            "backgroundPosition"   => "50% 50%",
                                            "backgroundRepeat"     => "no-repeat",
                                            "backgroundAttachment" => "scroll",
                                            "mediaId"              => "2"
                                        ]
                                    ],
                                    "active" => true
                                ]
                            ]
                        ]
                    ],
                    'cssState'     => 'normal',
                    'breakpointId' => 1
                ],
            ]
        ];

        $media = [
            [
                'id'        => 1,
                'name'      => 'test',
                'filename'  => 'test.jpeg',
                'extension' => 'jpeg'
            ],
            [
                'id'        => 2,
                'name'      => 'test',
                'filename'  => 'test.avif',
                'extension' => 'avif'
            ]
        ];

        $styleCollector
            ->assignMedia($media, fn (string $filename = null) => $filename)
            ->assignVariantsStyles($variantsStyles)
            ->assignBreakpoints($breakpoints)
            ->build();

        $generator = new CssGenerator($styleCollector);
        $css = $generator->generate();

        $expectedBreakpoint1 = '[data-widget-hash="random-hash"] {background: url(test.avif);background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;background-attachment: scroll;}';
        $expectedBreakpoint3 = '[data-widget-hash="random-hash"] {background: url(test.webp);background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;background-attachment: scroll;}';
        $this->assertEquals($expectedBreakpoint1, $css[1]);
        $this->assertEquals($expectedBreakpoint3, $css[3]);
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

        $expectedBreakpoint1 = '[data-widget-hash="random-hash"]:hover {color: rgb(0, 0, 0);}';
        $expectedBreakpoint3 = '[data-widget-hash="random-hash"] {font-family: "Helvetica";}';
        $this->assertEquals($expectedBreakpoint1, $css[1]);
        $this->assertEquals($expectedBreakpoint3, $css[3]);
    }

    protected function getStyleCollectorInstance(): StyleCollectorContract
    {
        return new StyleCollector();
    }
}
