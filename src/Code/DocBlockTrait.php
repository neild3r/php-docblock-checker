<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Trait for common docblock code
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
trait DocBlockTrait
{
    /** @var bool */
    protected $inherited = false;

    /**
     * @param array $input
     * @author Neil Brayfield <neil@d3r.com>
     */
    public static function fromArray(array $input): self
    {
        $parent = parent::fromArray($input);
        $parent->setInherited($input['inherited'] ?? false);

        return $parent;
    }

    /**
     * @param bool $inherited
     * @return self
     */
    public function setInherited(bool $inherited)
    {
        $this->inherited = $inherited;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInherited(): bool
    {
        return $this->inherited;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $parent = parent::jsonSerialize();
        $parent['inherited'] = $this->inherited;

        return $parent;
    }
}
