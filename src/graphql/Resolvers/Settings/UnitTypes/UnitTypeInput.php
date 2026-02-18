<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class UnitTypeInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeInput',
            'fields' => [
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                ],
            ],
        ]);
    }
}