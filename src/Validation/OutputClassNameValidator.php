<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Validation;

use ITB\ObjectTransformer\Exception\InvalidOutputClassName;

final class OutputClassNameValidator
{
    public static function validate($outputClassName): void
    {
        if (!is_string($outputClassName)) {
            throw InvalidOutputClassName::notString();
        }

        if (empty($outputClassName)) {
            throw InvalidOutputClassName::emptyString();
        }
    }
}
