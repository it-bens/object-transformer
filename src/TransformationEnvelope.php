<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer;

use ITB\ObjectTransformer\Exception\NotATransformationStamp;
use ITB\ObjectTransformer\Stamp\TransformationStampInterface;

final class TransformationEnvelope
{
    /** @var object $input */
    private object $input;
    /** @var TransformationStampInterface[] */
    private array $stamps = [];

    /**
     * @param object $input
     * @param TransformationStampInterface[] $stamps
     */
    public function __construct(object $input, array $stamps = [])
    {
        $this->input = $input;

        foreach ($stamps as $stamp) {
            if (!$stamp instanceof TransformationStampInterface) {
                throw NotATransformationStamp::new();
            }

            // Only one stamp per type is allowed. If this stamp is of a type that's already attached to this envelope,
            // it will not overwrite it if the existing stamp has the same or a higher priority.
            if (isset($this->stamps[get_class($stamp)]) && $this->stamps[get_class($stamp)]->getPriority() >= $stamp->getPriority()) {
                continue;
            }
            $this->stamps[get_class($stamp)] = $stamp;
        }
    }

    /**
     * @param object $input
     * @param TransformationStampInterface[] $stamps
     * @return static
     */
    public static function wrap(object $input, array $stamps = []): self
    {
        return $input instanceof self ? $input : new self($input, $stamps);
    }

    /**
     * @return object
     */
    public function getInput(): object
    {
        return $this->input;
    }

    /**
     * @param $type
     * @return TransformationStampInterface|null
     */
    public function getStamp($type): ?TransformationStampInterface
    {
        return $this->stamps[$type] ?? null;
    }

    /**
     * @return TransformationStampInterface[]
     */
    public function getStamps(): array
    {
        return $this->stamps;
    }

    /**
     * @param $type
     */
    public function removeStamp($type): void
    {
        unset($this->stamps[$type]);
    }
}
