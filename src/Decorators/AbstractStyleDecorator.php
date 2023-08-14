<?php

declare(strict_types=1);

namespace CssGenerator\Decorators;

abstract class AbstractStyleDecorator implements StyleDecoratorInterface
{
    /**
     * @return string
     */
    abstract public function __toString(): string;
}
