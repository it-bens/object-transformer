<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

use ITB\ObjectTransformer\TransformationEnvelope;
use ITB\ObjectTransformer\TransformerInterface;
use stdClass;

final class InvalidDummyTransformerOutputNotClass implements TransformerInterface
{
    public static function supportedTransformations(): array
    {
        /** @phpstan-ignore-next-line */
        return [['input' => Object1::class, 'output' => 'Blub.']];
    }

    public function transform(TransformationEnvelope $envelope, string $outputClassName): object
    {
        return new stdClass();
    }
}
