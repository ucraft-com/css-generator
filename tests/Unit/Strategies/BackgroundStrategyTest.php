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

        $expected = 'background: rgba(253, 247, 237, 1);';
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

        $expected = '';
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
                        'data'       => [
                            [
                                'color'    => 'rgba(179, 60, 60, 1)',
                                'position' => '0'
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"       => "linear",
                        "degree"     => "0deg",
                        "colorAlias" => "my-color-alias"
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: var(--color-my-color-alias, linear-gradient(0deg, rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%));background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundAndDataColorAlias_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data'   => [
                            [
                                'color'      => 'rgba(179, 60, 60, 1)',
                                'position'   => '0',
                                "colorAlias" => "my-color-alias"
                            ],
                            [
                                'color'    => 'rgba(255, 255, 255, 1)',
                                'position' => '1'
                            ],
                        ],
                        "type"   => "linear",
                        "degree" => "0deg",
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: linear-gradient(0deg, var(--color-my-color-alias, rgba(179, 60, 60, 1)) 0%, rgba(255, 255, 255, 1) 100%);background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenGradientBackgroundDataColorAliasAndNotGivenColor_ReturnsGradientCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data'   => [
                            [
                                'position' => '0',
                                "colorAlias"  => "my-color-alias"
                            ],
                            [
                                "colorAlias"  => "my-other-alias",
                                'position' => '1'
                            ],
                        ],
                        "type"   => "linear",
                        "degree" => "0deg",
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: linear-gradient(0deg, var(--color-my-color-alias) 0%, var(--color-my-other-alias) 100%);background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
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
                        'data'    => [
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
                        "colorAlias" => "my-color-alias"
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles);

        $expected = 'background: var(--color-my-color-alias, radial-gradient( rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%));background-size: auto;background-position: 0px 0px;background-repeat: no-repeat;background-attachment: scroll;';
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
                        'data'    => [
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
                        "colorAlias" => "my-color-alias"
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

        $expected = 'background: var(--color-my-color-alias, radial-gradient( rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%)), url(my/media/src/image.jpeg);background-size: auto, cover;background-position: 0px 0px, 50% 50%;background-repeat: no-repeat, no-repeat;background-attachment: scroll, scroll;';
        $this->assertEquals($expected, $css);
    }

    public function testConvert_WhenGivenImageBackgroundAndSources_ReturnsGeneratedBackgroundCss(): void
    {
        $variantsStyles = [
            'type'  => 'background',
            'value' => [
                [
                    'type'   => 'gradient',
                    'value'  => [
                        'data'    => [
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
                        "colorAlias" => "my-color-alias"
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
                            "destinationId"        => "1",
                            "sources"              => 'my-media.jpeg'
                        ],
                    ],
                    'active' => true
                ]
            ],
        ];

        $backgroundStrategy = new BackgroundStrategy();
        $css = $backgroundStrategy->convert($variantsStyles, [1 => 'my/media/src/image.jpeg', 'my-media.jpeg' => 'my/media/src/my-media.jpeg']);

        $expected = 'background: var(--color-my-color-alias, radial-gradient( rgba(179, 60, 60, 1) 0%, rgba(255, 255, 255, 1) 100%)), url(my/media/src/my-media.jpeg);background-size: auto, cover;background-position: 0px 0px, 50% 50%;background-repeat: no-repeat, no-repeat;background-attachment: scroll, scroll;';
        $this->assertEquals($expected, $css);
    }
}
