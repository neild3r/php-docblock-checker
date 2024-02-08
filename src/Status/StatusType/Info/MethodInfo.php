<?php

namespace PhpDocBlockChecker\Status\StatusType\Info;

/**
 * Info message for methods missing a docblock
 */
class MethodInfo extends Info
{
    /**
     * @var string
     */
    private $method;

    /**
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     * @author Neil Brayfield <neil@d3r.com>
     */
    public function __construct($file, $class, $line, $method)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'method';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . 'Method <info>' . $this->method . '</info> is missing a docblock.';
    }
}
