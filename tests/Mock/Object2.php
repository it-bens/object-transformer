<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

final class Object2
{
    /** @var int $letterCount */
    public int $letterCount;

    /**
     * @param $letterCount
     */
    public function __construct($letterCount)
    {
        $this->letterCount = $letterCount;
    }
}
