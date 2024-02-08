<?php

namespace Fixtures;

class TestNullable
{
    /**
     * @param string|null $nullableType
     * @param string|null $default
     */
    public function nullableVariations(?string $nullableType, string $default = null)
    {
    }
}
