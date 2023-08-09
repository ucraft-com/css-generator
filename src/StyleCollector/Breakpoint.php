<?php

declare(strict_types=1);

namespace CssGenerator\StyleCollector;

use function implode;

class Breakpoint
{
    /**
     * @var array<Style>
     */
    protected array $styles = [];

    /**
     * @var bool
     */
    protected bool $isDefault = false;

    /**
     * @var string @media query of breakpoint
     */
    protected string $mediaQuery = '';

    /**
     * @var int ID of breakpoint.
     */
    protected int $id;

    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $mediaQuery
     *
     * @return void
     */
    public function setMediaQuery(string $mediaQuery): void
    {
        $this->mediaQuery = $mediaQuery;
    }

    /**
     * @return string
     */
    public function getMediaQuery(): string
    {
        return $this->mediaQuery;
    }

    /**
     * @param bool $isDefault
     *
     * @return void
     */
    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param string                             $widgetId
     * @param \CssGenerator\StyleCollector\Style $style
     *
     * @return void
     */
    public function addStyle(string $widgetId, Style $style): void
    {
        $this->styles[$widgetId][] = $style;
    }

    /**
     * Bring together already generated css blocks.
     *
     * @return string
     */
    public function __toString()
    {
        foreach ($this->styles as $style) {
            $css = implode($style);
        }

        return $css ?? '';
    }
}
