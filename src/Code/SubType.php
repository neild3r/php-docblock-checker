<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

use PhpDocBlockChecker\Code\AbstractCode;

/**
 * Represents a single type not a composite type
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class SubType extends AbstractCode
{
    use SubTypesTrait;

    /**
     * @var \PhpDocBlockChecker\Code\AbstractCode
     * @author Neil Brayfield <neil@d3r.com>
     */
    protected $parent;

    /**
     * @var string
     * @author Neil Brayfield <neil@d3r.com>
     */
    protected $type;

    /**
     * @param string $type
     * @param \PhpDocBlockChecker\Code\AbstractCode $parent
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __construct(string $type, AbstractCode $parent)
    {
        $this->parent = $parent;
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function hasSubTypes(): bool
    {
        return count($this->types) > 1;
    }

    /**
     * @param SubType $subType
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function matches(SubType $subType): bool
    {
        if ($this->hasSubTypes()) {
            foreach ($this->types as $thisSubType) {
                foreach ($subType->getTypes() as $otherSubType) {
                    if ($thisSubType->matches($otherSubType)) {
                        return true;
                    }
                }
            }

            return false;
        }

        if ($subType->getType() === $this->getType()) {
            return true;
        }

        if ($subType->isSelfClass() && $this->isSelfClass()) {
            return true;
        }

        if ($subType->getType() === 'array' && $this->isTypedArray()) {
            return true;
        }

        if ($this->getType() === 'array' && $subType->isTypedArray()) {
            return true;
        }

        if ($subType->getFullyQualified() === $this->getFullyQualified()) {
            return true;
        }

        return false;
    }

    /**
     * Get the full qualified version of this variable
     *
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getFullyQualified(): string
    {
        if ($this->isPrimitive()) {
            return $this->type;
        }

        $uses = $this->parent->getUses();

        if (isset($uses[$this->type])) {
            $fqt = $uses[$this->type];
            return strpos($fqt, '\\') !== 0 ? '\\' . $fqt : $fqt;
        }

        return strpos($this->type, '\\') !== 0 ? '\\' . $this->type : $this->type;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __toString()
    {
        if (!$this->hasSubTypes()) {
            return $this->type;
        }

        $typesStringed = [];

        foreach ($this->types as $type) {
            $typesStringed[] = $type->toString();
        }

        return '(' . implode('&', $typesStringed) . ')';
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function toString(): string
    {
        return $this->__toString();
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isPrimitive(): bool
    {
        return in_array($this->type, [
            'bool',
            'int',
            'array',
            'mixed',
            'class',
            'object',
            'float',
            'string',
            'resource',
            'callable',
            'iterable',
            'void'
        ]);
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isSelfClass(): bool
    {
        if (in_array($this->type, ['static', 'self'])) {
            return true;
        }

        return $this->parent->getNamespace() . '\\' . $this->type === $this->parent->getFullClass();
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isTypedArray(): bool
    {
        return substr($this->type, -2, 2) === '[]';
    }

    /**
     * Needed for the cache
     *
     * @return mixed
     * @author Neil Brayfield <neil@d3r.com>
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->type;
    }
}
