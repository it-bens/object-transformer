<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidOutputClassName extends InvalidArgumentException
{
    public static function notClass(string $className): self
    {
        return new self(sprintf('The passed output class name \'%s\' is not an existing class.', $className));
    }
}
