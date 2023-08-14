<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

interface StyleDecoratorInterface
{
    public function __toString(): string;
}
