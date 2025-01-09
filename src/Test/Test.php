<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Test;

use PhpDocBlockChecker\sd;

class Test
{
    /**
     * @param bool $arg
     */
    public function singleParamShouldPass(bool $arg): void
    {
    }

    /**
     * @param bool|null $arg
     */
    public function singleParamShouldPass2(?bool $arg): void
    {
    }

    /**
     * @param bool|null $arg
     */
    public function singleParamShouldPass3(bool $arg = null): void
    {
    }

    /**
     * @return array
     */
    public function singleReturnShouldPass(): array
    {
        return [];
    }

    /**
     * @param bool|string|a $arg
     */
    public function unionParamShouldFail(bool|string $arg): void
    {
    }

    /**
     * @return iterable|\Countable|a
     */
    public function unionReturnShouldFail(): iterable|\Countable
    {
        return [];
    }

    /**
     * @param bool&string&a $arg
     */
    public function intersectionParamShouldFail(bool&string $arg): void
    {
    }

    /**
     * @return iterable&\Countable&a
     */
    public function intersectionReturnShouldFail(): iterable&\Countable
    {
        return [];
    }


    /**
     * @param bool|string $arg
     */
    public function unionParamPass(bool|string $arg): void
    {

    }

    /**
     * @param bool&string $arg
     */
    public function intersectionParamPass(bool&string $arg): void
    {

    }

    /**
     * @return bool
     */
    public function test3(): bool
    {
        return true;
    }

    /**
     * @return iterable&\Countable
     */
    public function intersectionReturnPass(): iterable&\Countable
    {
        return [];
    }

    /**
     * @return (iterable|\Countable)|array|null
     */
    public function dnf(): (iterable&\Countable)|array|null
    {
        return [];
    }
}
