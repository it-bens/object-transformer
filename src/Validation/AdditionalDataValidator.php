<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Validation;

use ITB\ObjectTransformer\Exception\InvalidAdditionalData;

final class AdditionalDataValidator
{
    public static function validate($additionalData): void
    {
        if (!is_array($additionalData)) {
            throw InvalidAdditionalData::notArray();
        }
    }
}
