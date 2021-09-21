<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Validation;

use ITB\ObjectTransformer\Exception\InvalidResult;

final class ResultValidator
{
    public static function validate($result): void
    {
        if (!is_object($result)) {
            throw InvalidResult::notObject();
        }
    }
}
