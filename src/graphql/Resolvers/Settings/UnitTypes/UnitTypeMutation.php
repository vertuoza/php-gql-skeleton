<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use RuntimeException;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
    static function get()
    {
        return [
            'unitTypeCreate' => [
                'type' => New NonNull(Types::get(UnitType::class)),
                'args' => [
                    'input' => New NonNull(Types::get(UnitTypeCreateInput::class))
                ],
                'resolve' => static function ($rootValue, $args, RequestContext $context) {
                    $name = (string)($args['input']['name'] ?? '');

                    $promise = $context->useCases->unitType->unitTypeCreate->handle($name);

                    return $promise->then(function ($entity) {
                        if ($entity === null) {
                            throw new RuntimeException('UnitType was created but could not be fetched');
                        }
                        return $entity;
                    });
                },
            ],
        ];
    }
}