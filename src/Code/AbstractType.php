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
    use SubTypesTrait;

    /** @var bool */
    protected $nullable = false;

    /**
     * @var string|null union, intersection, dnf or null
     */
    protected $compositeType;

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
        $method->setCompositeType($data['composite_type']);
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
    public function setCompositeType(?string $string): self
    {
        $this->compositeType = $string;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompositeType(): ?string
    {
        return $this->compositeType;
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
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __toString()
    {
        $separator = $this->getCompositeType() === 'intersection' ? '&' : '|';

        $typesStringed = [];

        foreach ($this->types as $type) {
            $typesStringed[] = $type->toString();
        }

        $string = implode($separator, $typesStringed);

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
     * @param AbstractCode $abstractCode
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addTypesFromString(string $type, AbstractCode $abstractCode): self
    {
        // Split for DNF
        $groups = preg_split('/[()]+/', $type, -1, PREG_SPLIT_NO_EMPTY);

        $isDNF = count($groups) > 1;

        if (!$isDNF) {
            // Now split for Union & Intersection
            $typesToAdd = preg_split('/(\||&)/', $type);

            foreach ($typesToAdd as $typeToAdd) {
                $this->addType($typeToAdd);
            }

            if (count($this->types) > 1) {
                $this->setCompositeType(strpos($type, '&') !== false ? 'intersection' : 'union');
            }

            return $this;
        }

        $this->setCompositeType('union');

        foreach ($groups as $groupType) {
            $groupType = ltrim($groupType, '|');
            $unionedTypes = explode('|', $groupType);

            foreach ($unionedTypes as $unionedType) {
                $types = explode('&', $unionedType);

                $addTo = $this;
                if (count($types) > 1) {
                    $addTo = new SubType('union', $this);
                    $addTo->setFromAbstract($abstractCode);

                    $this->addType($addTo);
                    $this->setCompositeType('dnf');
                }

                foreach ($types as $typeToAdd) {
                    $addTo->addType($typeToAdd);
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllTypedArray(): bool
    {
        foreach ($this->types as $type) {
            if (!$type->isTypedArray()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \PhpDocBlockChecker\Code\SubType $type
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function matches(SubType $type): bool
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
            'composite_type' => $this->compositeType,
        ]);
    }
}
