<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class UnsupportedInputClass extends InvalidArgumentException
{
    public static function new(string $inputClassName): self
    {
        return new self(
            sprintf(
                'The class of the passed input object \'%s\' is not supported by this transformer.',
                $inputClassName
            ),
            0,
            null
        );
    }
}
