<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

use ITB\ObjectTransformer\TransformationEnvelope;
use ITB\ObjectTransformer\TransformerInterface;
use stdClass;

final class InvalidDummyTransformerOutputNotString implements TransformerInterface
{
    public static function supportedTransformations(): array
    {
        return [['input' => Object1::class, 'output' => 1337]];
    }

    public function transform(TransformationEnvelope $envelope, string $outputClassName): object
    {
        return new stdClass();
    }
}
