<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

final class Object2
{
    /**
     * @param int $letterCount
     */
    public function __construct(public int $letterCount)
    {
    }
}
