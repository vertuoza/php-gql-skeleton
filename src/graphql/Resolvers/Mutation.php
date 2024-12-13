<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreateInput;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

final class Mutation extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => function () {
            return ['unitTypeCreate' => [
                'type' => new NonNull(Types::get(UnitType::class)),
                'args' => [
                    'input' => new NonNull(Types::get(UnitTypeCreateInput::class)),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypeCreate
                    ->handle(new UnitTypeMutationData($args['input']), $context)
            ]];
            }
        ]);
    }
}
