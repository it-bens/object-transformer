<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

use ITB\ObjectTransformer\Exception\UnsupportedInputClass;
use ITB\ObjectTransformer\TransformationEnvelope;
use ITB\ObjectTransformer\TransformerInterface;

final class DummyTransformer implements TransformerInterface
{
    public static function supportedTransformations(): array
    {
        return [['input' => Object1::class, 'output' => Object2::class]];
    }

    public function transform(TransformationEnvelope $envelope, string $outputClassName): object
    {
        $input = $envelope->getInput();
        if ($input instanceof Object1) {
            return new Object2(strlen($input->someString));
        }

        throw UnsupportedInputClass::new(get_class($input));
    }
}
