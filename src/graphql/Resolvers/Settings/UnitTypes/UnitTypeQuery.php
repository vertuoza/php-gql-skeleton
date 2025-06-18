<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Context\VertuozaContext;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;


class UnitTypeQuery
{
    static function get()
    {
        return [
            'unitTypeById' => [
                'description' => "Find a unit type using its unique identifier.",
                'type' => Types::get(UnitType::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->findById
                    ->handle($args['id'])
            ],
            'unitTypes' => [
                'description' => "Finds all unit types of the current user (tenant identifier).",
                'type' => new NonNull(new ListOfType(Types::get(UnitType::class))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->findMany
                    ->handle()
            ],
        ];
    }
}
