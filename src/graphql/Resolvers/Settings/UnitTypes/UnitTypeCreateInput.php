<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeCreateInput',
            'description' => 'Input object for creating a UnitType',
            'fields' => static fn () => [
                'name' => [
                    'type' => new NonNull(Types::string()),
                    'description' => 'Name of the UnitType',
                ],
            ],
        ]);
    }
}
