<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Code;

/**
 * Represents a portion of code belonging to a class
 *
 * @author Neil Brayfield <neil@d3r.com>
 */
abstract class AbstractCode
{
    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $line;

    /**
     * @var array
     */
    protected $uses;

    /**
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public static function factory(): self
    {
        return new static();
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     * @return self
     */
    public function setNamespace(string $namespace = null): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return self
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get full class
     *
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getFullClass(): string
    {
        if ($this->getNamespace()) {
            return $this->getNamespace() . '\\' . $this->getClass();
        }

        return $this->getClass();
    }

    /**
     * @return string
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function getFullyQualifiedClass(): string
    {
        return '\\' . $this->getFullClass();
    }

    /**
     * @return array
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * @param array $uses
     * @return self
     */
    public function setUses(array $uses): self
    {
        $this->uses = $uses;

        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return self
     */
    public function setLine(int $line): self
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Get the properties from an existing abstract
     *
     * @param AbstractCode $abstract
     * @return self
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function setFromAbstract(AbstractCode $abstract): self
    {
        $this->setClass($abstract->getClass());
        $this->setUses($abstract->getUses());
        $this->setLine($abstract->getLine());
        $this->setNamespace($abstract->getNamespace());
        return $this;
    }
}