<?php

namespace ITB\ObjectTransformer\Tests\Mock;

use ITB\ObjectTransformer\Stamp\TransformationStampInterface;

final class AdditionalDataStamp implements TransformationStampInterface
{
    /**
     * @phpstan-ignore-next-line
     * @param array $additionalData
     * @param int $priority
     */
    public function __construct(private array $additionalData, private int $priority = 0)
    {
    }

    /**
     * @phpstan-ignore-next-line
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
