# Css Generator

## Introduction

Welcome to the Css Generator! This library helps to generate css string from variants styles based on breakpoints.

## Installation

Install the Css Generator using [Composer](https://getcomposer.org/):

```bash
composer require ucraft-com/css-generator
```

## StyleCollector

The `StyleCollector` is used for collecting all the data that is needed for generating css.

### Usage Example

```php
use CssGenerator\StyleCollector\StyleCollector;

/*
 If you have static/global styles (that does not have breakpoints), style collector must be used like this

data example of static/global styles:
$staticGlobalStyles = [
    [
        'selector' => 'html',
        'styles'   => [
            'height' => 'auto',
        ],
    ],
]
 */
 $styleCollector = new StyleCollector();
 $styleCollector
    ->assignMedia($media, fn (string $filename = null) => storage_url(media_image_path($filename)))
    ->assignVariantsStyles($staticGlobalStyles)
    //->assignColorMediaQuery('@media (prefers-color-scheme: dark) {')
    ->buildWithBreakpointId($breakpointId); // $breakpointId is the value of concrete breakpoint, that style must be generated in, (usually default breakpoint)
// If you call here ->build(), it will return result like this [0 => 'all generated styles are here...']
````
After calling `generate()` method, it will return array data structure, which key will be `$breakpointId` that we provided earlier.
```php
$cssGenerator = new CssGenerator($styleCollector); // previously described style collector
$cssGenerator->generate(); // will return [$breakpointId => 'all generated styles are here...']
```
If you have styles that are generating with breakpoints we must assign `->assignBreakpoints($breakpoints)`, 
`$breakpoints` is array list of breakpoints

Example breakpoints:
```php
$breakpoint = [
    [
        'id'      => 1,
        'width'   => 1280,
        'default' => true
    ]
]
$styleCollector = new StyleCollector();
$styleCollector
    ->assignMedia($media, fn (string $filename = null) => storage_url(media_image_path($filename)))
    ->assignBreakpoints($breakpoints)
    ->assignVariantsStyles($styleData)
    //->assignColorMediaQuery('@media (prefers-color-scheme: dark) {')
    ->build(); // or ->buildWithoutBreakpoint(); which is internal will be called automatically when assignBreakpoints($breakpoints) is not called
```
Variants styles data example is like this:
```php
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
```
`assignMedia()` - the media that will be used for generating background, and resolver, for resolving media path.\
`assignBreakpoints()` - all breakpoints as array.\
`assignVariantsStyles()` - all style data, grouped by selector.\
`assignColorMediaQuery` - generate styles for dark or light mode.\
`build()` - convert data to corresponding data structures.\
`buildWithoutBreakpoint()` - Internal method, convert data to corresponding data structures without any breakpoints.\
`buildWithBreakpointId()` - Style will be generated on this breakpoint.

## CssGenerator

```php
use CssGenerator\CssGenerator;

$cssGenerator = new CssGenerator($styleCollector); // previously described style collector
$cssGenerator->generate(); // generates all css gouped by breakpoint ids like this: 
[
    1 => 'styles for 1 breakpoint id...', 
    2 => 'styles for 2 breakpoint id...', 
    ...
]
```
## License

The Css Generator is open-source software licensed under the [MIT License](LICENSE.md).
