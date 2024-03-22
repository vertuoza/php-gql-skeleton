<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
    static function get()
    {
        return [
            'unitTypeCreate' => [
                'type' => new NonNull(Types::get(UnitType::class)),
                'description' => "Create a unit type by supplying a name",
                'args' => [
                    'input' => new NonNull(Types::string())
                    //'input' => new NonNull(Types::get(UnitTypeCreateInput::class))
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypeCreate
                    ->handle($args['input'], $context)
            ]
        ];
    }
}
