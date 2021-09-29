<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

class Object1
{
    /**
     * @param string $someString
     */
    public function __construct(public string $someString)
    {
    }
}
