<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidOutputClassName extends InvalidArgumentException
{
    public static function notString(): self
    {
        return new self('The passed output class name is not a string. Only strings are allowed.');
    }

    public static function emptyString(): self
    {
        return new self('The passed output class name is empty.');
    }
}
