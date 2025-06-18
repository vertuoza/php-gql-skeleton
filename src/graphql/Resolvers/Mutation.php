<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreateInput;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

final class Mutation extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function () {
                return [
                    'unitTypeCreate' => [
                        'description' => "Creates a new unit type using an string as its name and avoids creating duplicates in case the mutation takes place using an existing unit type",
                        'type' => new NonNull(Types::get(UnitType::class)),
                        'args' => [
                            'input' => new NonNull(Types::get(UnitTypeCreateInput::class))
                        ],
                        'resolve' => static fn ($rootValue, $args, RequestContext $context)
                        => $context->useCases->unitType->initializer->setAndGetUnitType(new UnitTypeMutationData($args['input']['name']))
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}