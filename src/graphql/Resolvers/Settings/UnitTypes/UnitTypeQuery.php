<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Context\VertuozaContext;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Types\UnitType;


/**
 * GraphQL resolvers for querying UnitTypes.
 *
 *  - unitTypeById => Fetch a single UnitType by ID
 *  - unitTypes => Fetch all UnitTypes available for the current tenant
 */
class UnitTypeQuery
{
    static function get()
    {
        return [
            'unitTypeById' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypeById
                    ->handle($args['id'])
            ],
            'unitTypes' => [
                'type' => new NonNull(new ListOfType(Types::get(UnitType::class))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypesFindMany
                    ->handle()
            ],
        ];
    }
}
