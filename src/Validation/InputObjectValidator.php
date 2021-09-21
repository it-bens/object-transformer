<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Validation;

use ITB\ObjectTransformer\Exception\InvalidInputObject;

final class InputObjectValidator
{
    public static function validate($inputObject): void
    {
        if (!is_object($inputObject)) {
            throw InvalidInputObject::notObject();
        }
    }
}
