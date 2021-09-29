<?php

namespace ITB\ObjectTransformer\Stamp;

final class AdditionalDataStamp implements TransformationStampInterface
{
    /**
     * @param array $additionalData
     * @param int $priority
     */
    public function __construct(private array $additionalData, private int $priority = 0)
    {
    }

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
