<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

trait TypeCheckTrait
{
    /**
     * @param \PhpDocBlockChecker\Code\Param|\PhpDocBlockChecker\Code\ReturnType $docBlockType
     * @param \PhpDocBlockChecker\Code\Param|\PhpDocBlockChecker\Code\ReturnType $codeType
     * @return bool
     */
    public function isTypesValid($docBlockType, $codeType): bool
    {
        // First make sure both are either union or intersection types
        if ($docBlockType->getType() !== $codeType->getType()) {
            return false;
        }

        $codeTypes = $codeType->getTypes();

        // Then make sure all code types are in the docblock
        foreach ($codeTypes as $type) {
            if (!$docBlockType->hasType($type)) {
                return false;
            }
        }

        // Then make sure all types in docblock are actually in the code, unless no code types.
        if (!empty($codeTypes)) {
            foreach ($docBlockType->getTypes() as $type) {
                if (!$codeType->hasType($type)) {
                    return false;
                }
            }
        }

        return true;
    }
}
