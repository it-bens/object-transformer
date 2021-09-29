<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Exception;

use InvalidArgumentException;
use ITB\ObjectTransformer\TransformerInterface;

final class UnsupportedInputOutputTypes extends InvalidArgumentException
{
    /**
     * @param string $inputClassName
     * @param string $outputClassName
     * @param TransformerInterface[][] $transformers
     * @return static
     */
    public static function new(
        string $inputClassName,
        string $outputClassName,
        array $transformers
    ): self {
        // This flatten function only works for 2-dimensional arrays.
        $flatTransformers = array_merge(...array_values($transformers));
        $inputOutputTypesMap = array_map(
            static function (TransformerInterface $transformer) {
                $transformationConfigurations = array_map(
                    static function (array $transformationConfiguration) {
                        return sprintf(
                            '[\'input\': %s, \'output\': %s]',
                            $transformationConfiguration['input'],
                            $transformationConfiguration['output']
                        );
                    },
                    $transformer::supportedTransformations()
                );

                return implode(', ', $transformationConfigurations);
            },
            $flatTransformers
        );

        return new self(
            sprintf(
                'No transformer is registered for the input class \'%s\' (or one of it\'s interfaces) in combination with the output class \'%s\'. Supported combinations are [%s].',
                $inputClassName,
                $outputClassName,
                implode(', ', $inputOutputTypesMap)
            ),
            0,
            null
        );
    }
}
