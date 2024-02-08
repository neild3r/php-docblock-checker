<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

use PhpDocBlockChecker\Code\Param;
use PhpDocBlockChecker\Code\MethodDocBlock;

/**
 * Undocumented model
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
class Method extends AbstractCode implements \JsonSerializable
{

    /** @var string */
    protected $name;

    /** @var \PhpDocBlockChecker\Code\MethodDocBlock */
    protected $docBlock;

    /** @var bool */
    protected $hasReturn = false;

    /** @var \PhpDocBlockChecker\Code\ReturnType */
    protected $returnType;

    /** @var \PhpDocBlockChecker\Code\Param[] */
    protected $params = [];

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data)
    {
        /** @var Method $method */
        $method = parent::fromArray($data);
        if ($data['name']) {
            $method->setName($data['name']);
        }
        $method->setHasReturn($data['has_return']);
        if ($data['return_type']) {
            $method->setReturnType(ReturnType::fromArray($data['return_type']));
        }
        if ($data['doc_block']) {
            $method->setDocBlock(MethodDocBlock::fromArray($data['doc_block']));
        }
        foreach ($data['params'] as $param) {
            $method->addParam(Param::fromArray($param));
        }

        return $method;
    }

    /**     *
     * @param bool $bool
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setHasReturn(bool $bool): self
    {
        $this->hasReturn = $bool;

        return $this;
    }

    /**
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function hasReturn(): bool
    {
        return $this->hasReturn;
    }

    /**
     * @param ReturnType|null $returnType
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setReturnType(ReturnType $returnType = null): self
    {
        $this->returnType = $returnType;

        return $this;
    }

    /**
     * Get the return type
     *
     * @return ReturnType|null
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getReturnType(): ?ReturnType
    {
        return $this->returnType;
    }

    /**
     * @param string $name
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the docblock
     *
     * @param \PhpDocBlockChecker\Code\MethodDocBlock|null $docblock
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setDocBlock(MethodDocBlock $docblock = null): self
    {
        $this->docBlock = $docblock;

        return $this;
    }

    /**
     * @return \PhpDocBlockChecker\Code\MethodDocBlock|null
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getDocblock(): ?MethodDocBlock
    {
        return $this->docBlock;
    }

    /**
     * @param \PhpDocBlockChecker\Code\Param $param
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function addParam(Param $param): self
    {
        $this->params[$param->getName()] = $param;

        return $this;
    }

    /**
     * @return \PhpDocBlockChecker\Code\Param[]
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get the parameter by the name key
     *
     * @param string $name
     * @return \PhpDocBlockChecker\Code\Param|null
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getParam(string $name): ?Param
    {
        if (!$this->hasParam($name)) {
            return null;
        }

        return $this->params[$name];
    }

    /**
     * @param string $name
     * @return bool
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function hasParam(string $name): bool
    {
        return isset($this->params[$name]);
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
            'name' => $this->name,
            'params' => $this->params,
            'has_return' => $this->hasReturn,
            'return_type' => $this->returnType,
            'doc_block' => $this->getDocblock(),
        ]);
    }
}
