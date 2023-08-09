<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use CssGenerator\StrategyFactory;

class Style
{
    protected string $selector;

    protected array $rules = [];

    protected array $mediaMapping = [];

    public function setMediaMapping(array $mediaMapping): void
    {
        $this->mediaMapping = $mediaMapping;
    }

    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function __toString(): string
    {
        $css = '';
        $variantsStyleItem = $this->rules;
            // transform property name
            $transformCss = 'transform: ';

            // start css block
            $css .= !empty($variantsStyleItem['styles']) ? "{$this->selector} {".PHP_EOL : '';

            foreach ($variantsStyleItem['styles'] as $style) {
                $type = $style['type'];
                $value = $style['value'];

                // transform case
                if (isset($style['group']) && $style['group'] === 'transform') {
                    $transformCss .= "$type($value) "; // join transform styles separated by spaces
                    continue;
                }

                $css .= StrategyFactory::create($style['type'])->convert($style, $this->mediaMapping);
            }

            if ($transformCss !== 'transform: ') {
                $css .= rtrim($transformCss).';'.PHP_EOL; // add collected transform property, value
            }

            // close css block
            $css .= !empty($variantsStyleItem['styles']) ? '}'.PHP_EOL : '';

        return $css;
    }
}
