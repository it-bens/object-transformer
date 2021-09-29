<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Stamp;

final class InputClassStamp implements TransformationStampInterface
{
    /**
     * @param string $inputClassName
     * @param int $priority
     */
    public function __construct(private string $inputClassName, private int $priority = 0)
    {
    }

    /**
     * @return string
     */
    public function getInputClassName(): string
    {
        return $this->inputClassName;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
