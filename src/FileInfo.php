<?php

namespace PhpDocBlockChecker;

use PhpDocBlockChecker\Code\Method;

class FileInfo implements \JsonSerializable
{
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var array
     */
    private $classes;
    /**
     * @var array
     */
    private $methods;
    /**
     * @var int
     */
    private $mtime;

    /**
     * @param string $fileName
     * @param array $classes
     * @param array $methods
     * @param int $mtime
     */
    public function __construct($fileName, $classes, $methods, $mtime)
    {
        $this->fileName = $fileName;
        $this->classes = $classes;
        $this->methods = $methods;
        $this->mtime = $mtime;
    }

    /**
     * @param array $data
     * @return FileInfo
     */
    public static function fromArray(array $data)
    {
        $methods = [];

        foreach ($data['methods'] as $key => $raw) {
            $methods[$key] = Method::fromArray($raw);
        }

        return new self($data['fileName'], $data['classes'], $methods, $data['mtime']);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return int
     */
    public function getMtime()
    {
        return $this->mtime;
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
        return [
            'fileName' => $this->fileName,
            'mtime' => $this->mtime,
            'classes' => $this->classes,
            'methods' => $this->methods,
        ];
    }
}
