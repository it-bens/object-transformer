<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use RuntimeException;

final class NoTransformers extends RuntimeException
{
    public static function new(): self
    {
        return new self(
            'No transformers were passed to the mediator. At least one transformer should be registered when the mediator is used.',
            0,
            null
        );
    }
}
