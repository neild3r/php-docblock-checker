<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

use PhpDocBlockChecker\Code\SubType;

/**
 * Represents a type and contains comparison functions for dealing with
 * composite types
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
abstract class AbstractType extends AbstractCode
{
    /** @var array */
    protected $types = [];

    /** @var bool */
    protected $nullable = false;

    /**
     * @var string|null union or intersection
     */
    protected $type;

    /**
     * Create new instance using array data
     *
     * @param array $data
     * @return static
     * @author Neil Brayfield <neil@d3r.com>
     */
    public static function fromArray(array $data)
    {
        /** @var AbstractType $method */
        $method = parent::fromArray($data);
        $method->setNullable($data['nullable']);

        foreach ($data['types'] as $type) {
            $method->addType($type);
        }

        return $method;
    }

    /**
     * @param string|null $string
     * @return self
     */
    public function setType(?string $string): self
    {
        $this->type = $string;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param bool $bool
     * @return self
     */
    public function setNullable(bool $bool): self
    {
        $this->nullable = $bool;

        return $this;
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param array $types
     * @return self
     */
    public function addTypes(array $types): self
    {
        foreach ($types as $type) {
            $this->addType($type);
        }

        return $this;
    }

    /**
     * @param string $type
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addType(string $type): self
    {
        $this->types[] = new SubType($type, $this);
        return $this;
    }

    /**
     * @return array
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __toString()
    {
        $separator = $this->getType() === 'intersection' ? '&' : '|';

        $string = implode($separator, $this->types);

        if ($this->isNullable()) {
            $string .= $separator . 'null';
        }

        return $string;
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
     * Set the types using a string that could contain compound types
     *
     * @param string $type
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addTypesFromString(string $type): self
    {
        // Split for Union & Intersection
        $types = preg_split('/(\||&)/', $type);

        foreach ($types as $type) {
            if ($type === 'null') {
                $this->setNullable(true);
                continue;
            }
            $this->addType($type);
        }

        if (count($this->types) > 1) {
            $this->setType(strpos($type, '&') !== false ? 'intersection' : 'union');
        }

        return $this;
    }

    /**
     * @param \PhpDocBlockChecker\Code\SubType $type
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function hasType(SubType $type): bool
    {
        foreach ($this->getTypes() as $thisType) {
            if ($thisType->matches($type)) {
                return true;
            }
        }

        return false;
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
        $base = parent::jsonSerialize();

        return array_merge($base, [
            'nullable' => $this->nullable,
            'types' => $this->types,
        ]);
    }
}
