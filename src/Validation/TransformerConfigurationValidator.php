<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Validation;

use ITB\ObjectTransformer\Exception\InvalidTransformerConfiguration;

final class TransformerConfigurationValidator
{
    /**
     * @param array{"input": class-string, "output": class-string} $transformerConfiguration
     */
    public static function validate(array $transformerConfiguration): void
    {
        if (!array_key_exists('input', $transformerConfiguration)) {
            throw InvalidTransformerConfiguration::inputMissing();
        }
        if (!is_string($transformerConfiguration['input'])) {
            throw InvalidTransformerConfiguration::inputNotString();
        }
        if (!class_exists($transformerConfiguration['input'])) {
            throw InvalidTransformerConfiguration::inputNotClass();
        }

        if (!array_key_exists('output', $transformerConfiguration)) {
            throw InvalidTransformerConfiguration::outputMissing();
        }
        if (!is_string($transformerConfiguration['output'])) {
            throw InvalidTransformerConfiguration::outputNotString();
        }
        if (!class_exists($transformerConfiguration['output'])) {
            throw InvalidTransformerConfiguration::outputNotClass();
        }
    }
}
