<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeCreateInput',
            'description' => 'UnitTypeCreateInput"',
            'fields' => static fn (): array => [
                 'name' => [
                   'description' => "Name of the unit type",
                   'type' => New NonNull(Types::string())
                 ],
            ],
            'parseValue' => fn($values) => new UnitTypeMutationData(
                $values['name']
            )
        ]);
    }
}