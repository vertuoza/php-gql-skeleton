<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeCreateInput',
            'fields' => [
                'name' => Type::nonNull(Types::string()),
            ],
        ]);
    }
}