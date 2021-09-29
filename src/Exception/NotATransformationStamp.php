<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class NotATransformationStamp extends InvalidArgumentException
{
    public static function new(): self
    {
        return new self('The passed stamp is not an instance of the TransformerStampInterface.');
    }
}
