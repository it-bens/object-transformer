<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

use ITB\ObjectTransformer\Validation\InputObjectValidator;
use ITB\ObjectTransformer\Validation\OutputClassNameValidator;
use ITB\ObjectTransformer\Validation\ResultValidator;

final class Transformation
{
    private $inputObject;
    private $outputClass;
    private $result;

    /**
     * @param object $inputObject
     * @param string $outputClassName
     * @param object $result
     */
    public function __construct($inputObject, $outputClassName, $result)
    {
        InputObjectValidator::validate($inputObject);
        OutputClassNameValidator::validate($outputClassName);
        ResultValidator::validate($result);

        $this->inputObject = $inputObject;
        $this->outputClass = $outputClassName;
        $this->result = $result;
    }

    /**
     * @param object $inputObject
     * @param string $outputClassName
     * @return bool
     */
    public function equals($inputObject, $outputClassName): bool
    {
        InputObjectValidator::validate($inputObject);
        OutputClassNameValidator::validate($outputClassName);

        // The object hash is used instead the object id, because the data transformation
        // is the same for two different input objects with the same properties.
        if (spl_object_hash($inputObject) !== spl_object_hash($this->inputObject)) {
            return false;
        }

        if ($outputClassName !== $this->outputClass) {
            return false;
        }

        return true;
    }

    /**
     * @return object
     */
    public function getResult()
    {
        return $this->result;
    }
}
