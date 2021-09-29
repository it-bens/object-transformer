<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

interface TransformerInterface
{
    public static function supportedTransformations(): array;

    /**
     * @param TransformationEnvelope $envelope
     * @param string $outputClassName
     * @return object
     */
    public function transform(TransformationEnvelope $envelope, string $outputClassName): object;
}
