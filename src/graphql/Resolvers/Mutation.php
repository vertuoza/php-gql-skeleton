<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;


final class Mutation extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'unitTypeCreate' => [
                    'type' => Types::get(UnitType::class),
                    'args' => [
                        'name' => new NonNull(Types::string()),
                    ],
                    'resolve' => static fn($rootValue, $args, RequestContext $context)
                    => $context->useCases->unitType
                        ->unitTypeCreate
                        ->handle($args['name'], $context)
                ]
            ]
        ]);
    }
}
