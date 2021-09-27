<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

use ITB\ObjectTransformer\Exception\NoTransformers;
use ITB\ObjectTransformer\Exception\UnsupportedInputOutputTypes;
use ITB\ObjectTransformer\Validation\AdditionalDataValidator;
use ITB\ObjectTransformer\Validation\InputObjectValidator;
use ITB\ObjectTransformer\Validation\OutputClassNameValidator;
use ITB\ObjectTransformer\Validation\TransformerConfigurationValidator;

final class TransformationMediator implements TransformationMediatorInterface
{
    public const INPUT_CLASS_NAME = 'inputClassName';

    /**
     * @var TransformerInterface[][][]
     */
    private array $transformers = [];

    /** @var iterable $transformersGenerator */
    private iterable $transformersGenerator;
    /** @var bool $transformersPopulated */
    private bool $transformersPopulated = false;

    /**
     * @param iterable $transformers
     */
    public function __construct(iterable $transformers)
    {
        // The iteration over the transformers takes place in a separate method which is called once at runtime.
        // While iterating over the generator, the transformers are actually constructed.
        // If they require this commander as a construction argument, this leads to a circle reference.

        $this->transformersGenerator = $transformers;
    }

    /**
     * @param object $inputObject
     * @param string $outputClassName
     * @param array $additionalData
     * @return object
     */
    public function transform($inputObject, $outputClassName, $additionalData = [])
    {
        InputObjectValidator::validate($inputObject);
        OutputClassNameValidator::validate($outputClassName);
        AdditionalDataValidator::validate($additionalData);

        // Populate the transformers array if the commander is used the first time.
        if (false === $this->transformersPopulated) {
            $this->populateTransformers();
        }

        if (0 === count($this->transformers)) {
            throw NoTransformers::new();
        }

        // The actual object class can be misleading for finding a matching transformer.
        // E.g. Doctrine creates proxy classes for the managed entities.
        // However, a transformer would be registered for the actual class and not the proxy class.
        $inputClassName = get_class($inputObject);
        if (array_key_exists(self::INPUT_CLASS_NAME, $additionalData)) {
            $inputClassName = $additionalData[self::INPUT_CLASS_NAME];
            unset($additionalData[self::INPUT_CLASS_NAME]);
        }

        if (!isset($this->transformers[$inputClassName][$outputClassName])) {
            throw UnsupportedInputOutputTypes::new($inputClassName, $outputClassName, $this->transformers);
        }

        // Save the transformation to the transformation array (log).
        return $this->transformers[$inputClassName][$outputClassName]->transform(
            $inputObject,
            $outputClassName,
            $additionalData
        );
    }

    /**
     *
     */
    private function populateTransformers(): void
    {
        // The transformer instances are stored in a 2-dimensional array.
        // The first dimension is the input class, the second is the output class.
        // This allows 'categorizing' the transformers at service construction.

        foreach ($this->transformersGenerator as $transformer) {
            /** @var TransformerInterface $transformer */
            foreach ($transformer::supportedTransformations() as $transformerConfiguration) {
                TransformerConfigurationValidator::validate($transformerConfiguration);

                $supportedInputClass = $transformerConfiguration['input'];
                $supportedOutputClass = $transformerConfiguration['output'];

                if (!array_key_exists($supportedInputClass, $this->transformers)) {
                    $this->transformers[$supportedInputClass] = [$supportedOutputClass => $transformer];
                    continue;
                }

                $this->transformers[$supportedInputClass][$supportedOutputClass] = $transformer;
            }
        }

        $this->transformersPopulated = true;
    }
}
