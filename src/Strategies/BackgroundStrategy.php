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
        $styles = [];

        foreach ($variantStyle['value'] as $variantStyleValue) {
            if (!$variantStyleValue['active']) {
                continue;
            }

            $color = $this->parseValue($variantStyleValue['type'], $variantStyleValue['value'], $mediaMapping);

            $data = $variantStyleValue['type'] === 'image' ? $variantStyleValue['value']['data'] : $this->defaultBackgroundProps;
            $backgroundValue = $variantStyleValue['type'] === 'solid' ? "linear-gradient($color, $color)" : $color;

            $styles['background'][] = $backgroundValue;
            $styles['background-size'][] = $data['backgroundSize'];
            $styles['background-position'][] = $data['backgroundPosition'];
            $styles['background-repeat'][] = $data['backgroundRepeat'];
            $styles['background-attachment'][] = $data['backgroundAttachment'];
        }

        if (empty($styles)) {
            return 'background: none;';
        }

        return join('', [
            'background: '.join(', ', $styles['background']).';',
            'background-size: '.join(', ', $styles['background-size']).';',
            'background-position: '.join(', ', $styles['background-position']).';',
            'background-repeat: '.join(', ', $styles['background-repeat']).';',
            'background-attachment: '.join(', ', $styles['background-attachment']).';',
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
            return $this->parseGradient($value);
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

    /**
     * @param array $value
     *
     * @return string
     */
    protected function parseGradient(array $value): string
    {
        $colorId = $value['colorId'] ?? null;
        $gradientType = $value['type'];
        $degree = $value['degree'];

        $degreeValue = $gradientType === 'linear' && $degree ? "$degree," : '';

        $gradientCss = [];
        foreach ($value['data'] as $item) {
            $color = $item['color'];
            $position = $item['position'] * 100 .'%';
            $itemColorId = $item['colorId'] ?? null;

            $gradientCss[] = $itemColorId ? " var(--color-$itemColorId, $color) $position" : " $color $position";
        }

        $gradientCss = join(',', $gradientCss);
        $colorStr = "$gradientType-gradient($degreeValue$gradientCss)";

        return $colorId
            ? "var(--color-$colorId, $colorStr)"
            : $colorStr;
    }
}
