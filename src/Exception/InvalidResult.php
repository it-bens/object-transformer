<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidResult extends InvalidArgumentException
{
    public static function notObject(): self
    {
        return new self('The passed result is not an object. Only objects are allowed.');
    }
}
