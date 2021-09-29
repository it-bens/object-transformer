<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

use ITB\ObjectTransformer\Exception\UnsupportedInputClass;
use ITB\ObjectTransformer\TransformationEnvelope;
use ITB\ObjectTransformer\TransformerInterface;

final class DummyTransformerReverse implements TransformerInterface
{
    public static function supportedTransformations(): array
    {
        return [['input' => Object2::class, 'output' => Object1::class]];
    }

    public function transform(TransformationEnvelope $envelope, string $outputClassName): object
    {
        $input = $envelope->getInput();
        if ($input instanceof Object2) {
            return new Object1(substr('abcdefghijklmnopqrstuvwxyz', 0, $input->letterCount));
        }

        throw UnsupportedInputClass::new(get_class($input));
    }
}
