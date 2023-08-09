<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

class Breakpoint
{
    protected array $styles = [];

    protected bool $isDefault = false;

    protected int $width;

    protected string $mediaQuery = '';

    protected int $id;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setMediaQuery(string $mediaQuery)
    {
        $this->mediaQuery = $mediaQuery;
    }

    public function getMediaQuery(): string
    {
        return $this->mediaQuery;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function addStyle(string $widgetId, Style $style): void
    {
        $this->styles[$widgetId][] = $style;
    }

    public function __toString()
    {
        $css = '';
        foreach ($this->styles as $style) {
            foreach ($style as $item) {
                $css .= $item;
            }
        }

        return $css;
    }
}
