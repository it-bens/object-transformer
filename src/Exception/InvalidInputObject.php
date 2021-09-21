<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidInputObject extends InvalidArgumentException
{
    public static function notObject(): self
    {
        return new self('The passed input object is not an object. Only objects are allowed.');
    }
}
