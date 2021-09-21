<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;

final class InvalidTransformerConfiguration extends InvalidArgumentException
{
    public static function inputMissing(): self
    {
        return new self('The passed transformation configuration contains no input key.');
    }

    public static function inputNotString(): self
    {
        return new self('The passed transformation configuration input property is not a string.');
    }

    public static function inputNotClass(): self
    {
        return new self('The passed transformation configuration input property is not an existing class.');
    }

    public static function outputMissing(): self
    {
        return new self('The passed transformation configuration contains no ouput key.');
    }

    public static function outputNotString(): self
    {
        return new self('The passed transformation configuration output property is not a string.');
    }

    public static function outputNotClass(): self
    {
        return new self('The passed transformation configuration output property is not an existing class.');
    }
}
