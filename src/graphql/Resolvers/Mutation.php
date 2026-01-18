<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreateInput;

final class Mutation extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => function () {
                return [
                    'unitTypeCreate' => [
                        'type' => Type::nonNull(Types::get(UnitType::class)),
                        'args' => [
                            'input' => Type::nonNull(Types::get(UnitTypeCreateInput::class)),
                        ],
                        'resolve' => function ($root, $args, $context) {
                            return $context->useCases->unitType->unitTypeCreate->execute($args['input']);
                        }
                    ],
                ];
            }
        ]);
    }
}