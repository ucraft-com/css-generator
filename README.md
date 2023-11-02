# Css Generator

## Introduction

Welcome to the Css Generator! This library helps to generate css string from varaints styles.

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

$styleCollector = new StyleCollector();
$styleCollector
    ->assignMedia($media, fn (string $filename = null) => storage_url(media_image_path($filename)))
    ->assignBreakpoints($breakpoints)
    ->assignVariantsStyles($styleData)
    //->assignColorMediaQuery('@media (prefers-color-scheme: dark) {')
    ->build(); // or ->buildWithoutBreakpoint(); which is internal
/*
will be called automatically when assignBreakpoints($breakpoints) is not called, variants styles data shape in that case is like this
$staticGlobalStyles = [
    [
        'selector' => 'html',
        'styles'   => [
            'height' => 'auto',
        ],
    ],
]

assignMedia - the media that will be used for generating background, and resolver, for resolving media path.
assignBreakpoints - all breakpoints sorted by width.
assignVariantsStyles - all style data, grouped by selector. ex: loop variants styles and collect - $styleData[$selector][] = $style;
assignColorMediaQuery - generate styles for dark or light mode.
build - convert data to coresponding data structures.
buildWithoutBreakpoint - convert data to coresponding data structures without any breakpoints.
*/
```

## CssGenerator

```php
use CssGenerator\CssGenerator;

$cssGenerator = new CssGenerator($styleCollector); // previously described style collector
$cssGenerator->generate(); // generates all css as string based on collected styles.
```

## License

The Css Generator is open-source software licensed under the [MIT License](LICENSE.md).
