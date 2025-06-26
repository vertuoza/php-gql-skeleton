<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
    private static function toDto(array $input): UnitTypeMutationData
    {
        $dto = new UnitTypeMutationData();
        $dto->name = $input['name'];
        return $dto;
    }


    static function get()
    {
        return [
            'unitTypeCreate' => [
                'type' => Types::id(),
                'args' => [
                    'input' => new NonNull(Types::get(UnitTypeInputMutation::class)),
                ],
                'resolve' => static fn($rootValue, $args, RequestContext $context)
                => $context->useCases->unitType
                    ->unitTypeCreate
                    ->handle(self::toDto($args['input']), $context)
            ]
        ];
    }
}
