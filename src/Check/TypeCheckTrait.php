<?php

declare(strict_types=1);

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\Code\AbstractType;

trait TypeCheckTrait
{
    /**
     * @param \PhpDocBlockChecker\Code\AbstractType $docBlockType
     * @param \PhpDocBlockChecker\Code\AbstractType $codeType
     * @return bool
     */
    public function isTypesValid(AbstractType $docBlockType, AbstractType $codeType): bool
    {
        $codeTypes = $codeType->getTypes();

        if (empty($codeTypes)) {
            return true;
        }

        // First make sure both are either union, intersection, dnf or null types,
        // but allow code to be array but docblock to be union of different types but in array form
        if ($docBlockType->getCompositeType() !== $codeType->getCompositeType()) {
            if ($codeType->getCompositeType() || !$docBlockType->isAllTypedArray()) {
                return false;
            }
        }

        // Then make sure all code types are in the docblock
        foreach ($codeTypes as $type) {
            if (!$docBlockType->matches($type)) {
                return false;
            }
        }

        // Then make sure all types in docblock are actually in the code
        foreach ($docBlockType->getTypes() as $type) {
            if (!$codeType->matches($type)) {
                return false;
            }
        }

        return true;
    }
}
