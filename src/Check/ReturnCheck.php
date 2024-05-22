<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMismatchWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMissingWarning;

class ReturnCheck extends Check
{
    use TypeCheckTrait;

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            $docblock = $method->getDocblock();
            if ($docblock === null || !$method->hasReturn() || $method->getReturnType() === null) {
                // Nothing to check.
                continue;
            }

            if ($docblock->getReturnType() === null) {
                $this->fileStatus->add(
                    new ReturnMissingWarning(
                        $file->getFileName(),
                        $name,
                        $method->getLine(),
                        $name
                    )
                );
                continue;
            }

            $docBlockTypes = $docblock->getReturnType();
            $methodTypes = $method->getReturnType();

            if (!$this->isTypesValid($docBlockTypes, $methodTypes)) {
                $this->fileStatus->add(
                    new ReturnMismatchWarning(
                        $file->getFileName(),
                        $name,
                        $method->getLine(),
                        $name,
                        $methodTypes->toString(),
                        $docBlockTypes->toString(),
                    )
                );

                continue;
            }

            if ($methodTypes->isNullable() !== $docBlockTypes->isNullable()) {
                $this->fileStatus->add(
                    new ReturnMismatchWarning(
                        $file->getFileName(),
                        $name,
                        $method->getLine(),
                        $name,
                        $methodTypes->toString(),
                        $docBlockTypes->toString(),
                    )
                );
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipSignatures();
    }
}
