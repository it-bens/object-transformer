<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests\Mock;

class Object1
{
    /** @var string $someString */
    public string $someString;

    /**
     * @param $someString
     */
    public function __construct($someString)
    {
        $this->someString = $someString;
    }
}
