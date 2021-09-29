<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

interface TransformationMediatorInterface
{
    /**
     * @param object $input
     * @param string $outputClassName
     * @return object
     */
    public function transform(object $input, string $outputClassName): object;
}
