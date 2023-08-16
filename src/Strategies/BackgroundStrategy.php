<?php

declare(strict_types=1);

namespace CssGenerator\Strategies;

use function join;

class BackgroundStrategy implements StrategyInterfaceWithMediaMapping
{
    /**
     * @var array|string[]
     */
    protected array $defaultBackgroundProps = [
        'backgroundSize'       => 'auto',
        'backgroundPosition'   => '0px 0px',
        'backgroundRepeat'     => 'no-repeat',
        'backgroundAttachment' => 'scroll',
    ];

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
        $backgroundImages = [];
        $backgroundSizes = [];
        $backgroundPositions = [];
        $backgroundRepeats = [];
        $backgroundAttachment = [];

        foreach ($variantStyle['value'] as $variantStyleValue) {
            if (!$variantStyleValue['active']) {
                continue;
            }

            $color = $this->parseValue($variantStyleValue['type'], $variantStyleValue['value'], $mediaMapping);

            $data = $variantStyleValue['type'] === 'image' ? $variantStyleValue['value']['data'] : $this->defaultBackgroundProps;
            $backgroundSizes[] = $data['backgroundSize'];
            $backgroundPositions[] = $data['backgroundPosition'];
            $backgroundRepeats[] = $data['backgroundRepeat'];
            $backgroundAttachment[] = $data['backgroundAttachment'];

            $backgroundValue = $variantStyleValue['type'] === 'solid' ? "linear-gradient($color, $color)" : $color;
            $backgroundImages[] = $backgroundValue;
        }

        $backgroundImages = join(', ', $backgroundImages);
        $backgroundSizes = join(', ', $backgroundSizes);
        $backgroundPositions = join(', ', $backgroundPositions);
        $backgroundRepeats = join(', ', $backgroundRepeats);
        $backgroundAttachment = join(', ', $backgroundAttachment);

        return join('', [
            $backgroundImages ? "background: $backgroundImages;" : "background: none;",
            $backgroundSizes ? "background-size: $backgroundSizes;" : '',
            $backgroundPositions ? "background-position: $backgroundPositions;" : '',
            $backgroundRepeats ? "background-repeat: $backgroundRepeats;" : '',
            $backgroundAttachment ? "background-attachment: $backgroundAttachment;" : ''
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
            return $this->parseImage($value, $mediaMapping);
        }

        if ($type === 'gradient') {
            $colorId = $value['colorId'] ?? null;
            $gradientData = $value['data'];
            $gradientType = $value['type'];
            $degree = $value['degree'];

            $gradient = "$gradientType-gradient";
            $degreeValue = $gradientType === 'linear' && $degree ? "$degree," : '';

            $gradientCss = [];
            foreach ($gradientData as $item) {
                $color = $item['color'];
                $position = $item['position'] * 100 .'%';
                $itemColorId = $item['colorId'] ?? null;

                $gradientCss[] = $itemColorId ? " var(--color-$itemColorId, $color) $position" : " $color $position";
            }

            $gradientCss = join(',', $gradientCss);
            $colorStr = "$gradient($degreeValue$gradientCss)";

            return $colorId
                ? "var(--color-$colorId, $colorStr)"
                : $colorStr;
        }

        return $value;
    }

    /**
     * @param array $value
     * @param array $mediaMapping
     *
     * @return string
     */
    protected function parseImage(array $value, array $mediaMapping): string
    {
        $mediaSrc = $mediaMapping[(int)$value['data']['mediaId']] ?? null;

        return $mediaSrc ? "url($mediaSrc)" : 'none';
    }
}
