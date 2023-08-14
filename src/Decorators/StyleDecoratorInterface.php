<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

interface StyleDecoratorInterface
{
    /**
     * @return string
     */
    public function __toString(): string;
}
