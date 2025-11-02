<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Types\UnitType;
use GraphQL\Type\Definition\NonNull;

class UnitTypeMutation
{
    /**
     * Defines GraphQL mutations for UnitType
     * - allows creation of a UnitType by a name
     */
    public static function get(): array
    {
        return [
            'unitTypeCreate' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'input' => new NonNull(Types::get(UnitTypeCreateInput::class)),
                ],
                'resolve' => static fn($root, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypeCreate
                    ->handle($args['input']['name'])
            ],
        ];
    }
}