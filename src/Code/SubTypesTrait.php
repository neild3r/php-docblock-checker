<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

trait SubTypesTrait
{
    /** @var array */
    protected $types = [];

    /**
     * @param string[]|\PhpDocBlockChecker\Code\SubType[] $types
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
     * @param string|SubType $type
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addType($type): self
    {
        if ($type instanceof SubType) {
            $this->types[] = $type;
        } else {
            if ($type === 'null') {
                $this->setNullable(true);
            } else {
                $this->types[] = new SubType($type, $this);
            }
        }

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
}
