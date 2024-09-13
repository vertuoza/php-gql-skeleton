<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\Inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeCreateInput',
            'fields' => static fn (): array => [
                'name' => [
                    'description' => "Name of the new unit type",
                    'type' => new NonNull(Types::string())
                ],
            ],
        ]);
    }
}
