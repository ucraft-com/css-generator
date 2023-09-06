<?php

declare(strict_types=1);

namespace CssGenerator\Tests\Unit\Strategies;

use CssGenerator\Strategies\BackgroundStrategy;
use PHPUnit\Framework\TestCase;

class BackgroundStrategyTest extends TestCase
{
    public function testConvert_WhenGivenSolidBackground_ReturnsGeneratedCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    "type"   => "solid",
                    "value"  => "rgba(253, 247, 237, 1)",
                    "active" => true
                ]
            ]
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: linear-gradient(rgba(253, 247, 237, 1), rgba(253, 247, 237, 1));background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenSolidBackgroundAndActiveIsFalse_ReturnsNone(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    "type"   => "solid",
                    "value"  => "rgba(253, 247, 237, 1)",
                    "active" => false
                ]
            ]
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: none;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenImageBackgroundAndMediaId_ReturnsImageCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'image',
                    'value'  => [
                        'data' => [
                            "backgroundSize"       => "cover",
                            "backgroundPosition"   => "50% 50%",
                            "backgroundRepeat"     => "no-repeat",
                            "backgroundAttachment" => "scroll",
                            "mediaId"              => "1"
                        ],
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles, [1 => 'my/media/src/image.jpeg']);

        $expected = 'background: url(my/media/src/image.jpeg);background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenImageBackgroundAndMediaDoesNotExists_ReturnsBackgroundNone(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'image',
                    'value'  => [
                        'data' => [
                            "backgroundSize"       => "cover",
                            "backgroundPosition"   => "50% 50%",
                            "backgroundRepeat"     => "no-repeat",
                            "backgroundAttachment" => "scroll",
                            "mediaId"              => "2"
                        ],
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles, [1 => 'my/media/src/image.jpeg']);

        $expected = 'background: none;background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackground_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data' => [
                            [
                                'color'    => 'rgba(179, 60, 60, 1)',
                                'position' => '0'
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"    => "linear",
                        "degree"  => "0deg",
                        "colorId" => "20"
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: var(--color-20, linear-gradient(0deg, rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%));background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundAndDataColorId_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data' => [
                            [
                                'color'    => 'rgba(179, 60, 60, 1)',
                                'position' => '0',
                                "colorId" => "20"
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"    => "linear",
                        "degree"  => "0deg",
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: linear-gradient(0deg, var(--color-20, rgba(179, 60, 60, 1)) 0%, rgba(255, 255, 255, 1) 100%);background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundDataColorIdAndNotGivenColor_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data' => [
                            [
                                'position' => '0',
                                "colorId" => "20"
                            ],
                            [
                                "colorId" => "1",
                                'position' => '1'
                            ],
                        ],
                        "type"    => "linear",
                        "degree"  => "0deg",
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: linear-gradient(0deg, var(--color-20) 0%, var(--color-1) 100%);background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundForRadialType_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data' => [
                            [
                                'color'    => 'rgba(179, 60, 60, 1)',
                                'position' => '0'
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"    => "radial",
                        "degree"  => "0deg",
                        "colorId" => "20"
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: var(--color-20, radial-gradient( rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%));background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundAndImageBackground_ReturnsGeneratedBackgroundCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data' => [
                            [
                                'color'    => 'rgba(179, 60, 60, 1)',
                                'position' => '0'
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"    => "radial",
                        "degree"  => "0deg",
                        "colorId" => "20"
                    ],
                    'active' => true
                ],
                [
                    'type'   => 'image',
                    'value'  => [
                        'data' => [
                            "backgroundSize"       => "cover",
                            "backgroundPosition"   => "50% 50%",
                            "backgroundRepeat"     => "no-repeat",
                            "backgroundAttachment" => "scroll",
                            "mediaId"              => "1"
                        ],
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles, [1 => 'my/media/src/image.jpeg']);

        $expected = 'background: var(--color-20, radial-gradient( rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%)), url(my/media/src/image.jpeg);background-size: auto, cover;background-position: 0px 0px, 50% 50%;background-repeat: no-repeat, no-repeat;background-attachment: scroll, scroll;';
        $this->assertEquals($expected, $css);
    }
}
