<?php

namespace PhpDocBlockChecker\Test\Fixture;

class TestClassPhp7
{
    /**
     * @return string
     */
    public function withReturnHint(): string
    {
        return 'test';
    }

    /**
     * @return string|null
     */
    public function withNullableReturnHint(): ?string
    {
        return 'test';
    }

    /**
     * @return null|string
     */
    public function withMixedOrderNullableReturnHint(): ?string
    {
        return 'test';
    }
}
