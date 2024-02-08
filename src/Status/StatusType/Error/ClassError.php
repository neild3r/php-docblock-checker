<?php

namespace PhpDocBlockChecker\Status\StatusType\Error;

class ClassError extends Error
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'class';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . 'Class <info>' . $this->class . '</info> is missing a docblock.';
    }
}
