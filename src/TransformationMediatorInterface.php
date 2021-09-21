<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

interface TransformationMediatorInterface
{
    /**
     * @param object $inputObject
     * @param string $outputClassName
     * @param array $additionalData
     * @return object
     */
    public function transform($inputObject, $outputClassName, $additionalData = []);
}
