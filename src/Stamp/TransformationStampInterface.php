<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Stamp;

interface TransformationStampInterface
{
    /**
     * Multiple stamps of the same type can be passed to the envelope,
     * but it will overwrite a stamp if a higher-priority stamp of the same type is found.
     *
     * @return int
     */
    public function getPriority(): int;
}
