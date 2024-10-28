<?php

namespace Vertuoza\Types;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Errors\FieldError;

class QueryType extends ObjectType
{
    /**
     * @param string $name
     * @return bool
     *
     * @throws FieldError
     */
    public function hasField(string $name): bool
    {
        return true; // parent::hasField($name); // ?: throw new FieldError($name, "Field '$name' does not exist.");
    }
}