<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Context\RequestContext;

use function React\Async\await;

class UnitTypeMutation
{
    public static function get(): array
    {
        return [
            'createUnitType' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'name' => Type::nonNull(Type::string()),
                ],
                'resolve' => static function ($rootValue, array $args, RequestContext $context) {
                    return await($context->useCases
                        ->unitType
                        ->createUnitType
                        ->handle($args['name']));
                },
            ],
        ];
    }
}
