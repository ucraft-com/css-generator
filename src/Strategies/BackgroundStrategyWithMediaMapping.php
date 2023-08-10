<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

use function join;

class BackgroundStrategyWithMediaMapping implements StrategyInterfaceWithMediaMapping
{
    /**
     * Converts given variantsStyles to relative css string.
     *
     * @param array $variantStyle
     * @param array $mediaMapping
     *
     * @return string
     */
    public function convert(array $variantStyle, array $mediaMapping = []): string
    {
        $backgroundProps = [
            'backgroundSize'       => 'auto',
            'backgroundPosition'   => '0px 0px',
            'backgroundRepeat'     => 'no-repeat',
            'backgroundAttachment' => 'scroll',
        ];

        $imageProperty = 'background';
        $sizeProperty = 'background-size';
        $positionProperty = 'background-position';
        $repeatProperty = 'background-repeat';
        $attachmentProperty = 'background-attachment';

        $backgroundImages = [];
        $backgroundSizes = [];
        $backgroundPositions = [];
        $backgroundRepeats = [];
        $backgroundAttachment = [];

        foreach ($variantStyle['value'] as $variantStyleValue) {
            $color = $this->parseValue($variantStyleValue['type'], $variantStyleValue['value'], $mediaMapping);

            $data = $variantStyleValue['type'] === 'image' ? $variantStyleValue['value']['data'] : $backgroundProps;
            $backgroundSizes[] = $data['backgroundSize'];
            $backgroundPositions[] = $data['backgroundPosition'];
            $backgroundRepeats[] = $data['backgroundRepeat'];
            $backgroundAttachment[] = $data['backgroundAttachment'];

            $backgroundValue = $variantStyleValue['type'] === 'solid' && $variantStyleValue['active'] ? "linear-gradient($color, $color)" : $color;
            $backgroundImages[] = $backgroundValue;
        }

        $backgroundImages = join(', ', $backgroundImages);
        $backgroundSizes = join(', ', $backgroundSizes);
        $backgroundPositions = join(', ', $backgroundPositions);
        $backgroundRepeats = join(', ', $backgroundRepeats);
        $backgroundAttachment = join(', ', $backgroundAttachment);

        return join(PHP_EOL, [
            "$imageProperty: $backgroundImages;",
            "$sizeProperty: $backgroundSizes;",
            "$positionProperty: $backgroundPositions;",
            "$repeatProperty: $backgroundRepeats;",
            "$attachmentProperty: $backgroundAttachment;"
        ]);
    }

    /**
     * @param string       $type
     * @param string|array $value
     * @param array        $mediaMapping
     *
     * @return string
     */
    protected function parseValue(string $type, string|array $value, array $mediaMapping): string
    {
        if ($type === 'image') {
            $mediaId = (int)$value['data']['mediaId'] ?? null;

            if ($mediaId) {
                $mediaSrc = $mediaMapping[$mediaId] ?? '';

                return "url($mediaSrc)";
            }

            return 'none';
        }

        if ($type === 'gradient') {
            $colorId = $value['data']['colorId'] ?? null;
            $gradientData = $value['data'];
            $gradientType = $value['type'];
            $degree = $value['degree'];

            $gradient = "$gradientType-gradient";
            $degreeValue = $gradientType === 'linear' && $degree ? "$degree," : '';

            $gradientCss = '';
            foreach ($gradientData as $item) {
                $color = $item['color'] ?? null;
                $position = $item['position'];
                $itemColorId = $item['colorId'] ?? null;

                if ($color) {
                    $gradientCss .= $itemColorId ? " var(--color-$itemColorId, $color)" : ' '.$color;
                    $gradientCss .= ' '.$position * 100 .'%,';
                } elseif ($itemColorId) {
                    $gradientCss .= " var(--color-$itemColorId) ".$position * 100 .'%';
                }
            }

            $gradientCss = rtrim(',', $gradientCss);
            $colorStr = "$gradient($degreeValue$gradientCss)";

            return $colorId
                ? "var(--color-$colorId, $colorStr)"
                : $colorStr;
        }

        return $value['color'] ?? $value;
    }
}
