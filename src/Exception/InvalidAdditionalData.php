<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidAdditionalData extends InvalidArgumentException
{
    public static function notArray(): self
    {
        return new self('The passed additional data is not an array. Only arrays are allowed.');
    }
}
