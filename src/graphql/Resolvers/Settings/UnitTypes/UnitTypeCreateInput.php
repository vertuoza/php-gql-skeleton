<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\InputObjectType;

class UnitTypeCreateInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([

            'name' => 'UnitTypeCreateInput ',
            'fields' => [
                'name' => [
                    'type' => new NonNull(Types::string()),
                    'description' => 'Name of the unit type'
                ]
            ]
        ]);
    }
}
