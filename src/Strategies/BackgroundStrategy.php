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
            file_put_contents('logs.txt', $color.PHP_EOL , FILE_APPEND | LOCK_EX);

            $data = $variantStyleValue['type'] === 'image' ? $variantStyleValue['value']['data'] : $this->defaultBackgroundProps;
            $backgroundValue = $variantStyleValue['type'] === 'solid' ? "linear-gradient($color, $color)" : $color;

            $styles['background'][] = $backgroundValue;
            $styles['background-size'][] = $data['backgroundSize'] ?? $this->defaultBackgroundProps['backgroundSize'];
            $styles['background-position'][] = $data['backgroundPosition'] ?? $this->defaultBackgroundProps['backgroundPosition'];
            $styles['background-repeat'][] = $data['backgroundRepeat'] ?? $this->defaultBackgroundProps['backgroundRepeat'];
            $styles['background-attachment'][] = $data['backgroundAttachment'] ?? $this->defaultBackgroundProps['backgroundAttachment'];
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
        $mediaSrc = null;

        // In case of blocks we don't have mediaId, we have sources
        if (!empty($value['data']['mediaId'])) {
            $mediaSrc = $mediaMapping[(int)$value['data']['mediaId']] ?? null;
        } elseif (!empty($value['data']['sources'])) {
            $sources = $value['data']['sources'];
            $mediaSrc = $mediaMapping[$sources] ?? null;
        } elseif (!empty($value['data']['url'])) {
            $mediaSrc = $value['data']['url'];
        }

        return $mediaSrc ? "url($mediaSrc)" : 'none';
    }

    /**
     * @param array $value
     *
     * @return string
     */
    protected function parseGradient(array $value): string
    {
        $colorAlias = $value['colorAlias'] ?? null;
        $gradientType = $value['type'];
        $degree = $value['degree'];

        $degreeValue = $gradientType === 'linear' && $degree ? "$degree," : '';

        $gradientCss = [];
        foreach ($value['data'] as $item) {
            $color = $item['color'] ?? null;
            $position = $item['position'] * 100 .'%';
            $itemColorAlias = $item['colorAlias'] ?? null;

            if ($itemColorAlias && $color) {
                $gradientCss[] = " var(--color-$itemColorAlias, $color) $position";
            } elseif ($itemColorAlias) {
                $gradientCss[] = " var(--color-$itemColorAlias) $position";
            } elseif ($color) {
                $gradientCss[] = " $color $position";
            }
        }

        $gradientCss = join(',', $gradientCss);
        $colorStr = "$gradientType-gradient($degreeValue$gradientCss)";

        return $colorAlias
            ? "var(--color-$colorAlias, $colorStr)"
            : $colorStr;
    }
}
