<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
    static function get()
    {
        return [
            'unitTypeCreate' => [
                'type' => new NonNull(Types::get(UnitType::class)),
                'args' => [
                    'input' => new NonNull(Types::get(UnitTypeCreateInput::class)()),
                    ],
                'resolve' => static function ($rootValue, array $args, RequestContext $context) {
                    $mutationData = new UnitTypeMutationData();
                    $mutationData->name = $args['input']['name'];

                    return $context->useCases->unitType
                        ->unitTypeCreate
                        ->handle($mutationData);
                },
            ],
        ];
    }
}