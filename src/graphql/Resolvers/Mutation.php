<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeMutation;

class Mutation extends ObjectType
{
    public function __construct()
    {
        // Définition de l'input type
        $unitTypeCreateInput = new InputObjectType([
            'name' => 'UnitTypeCreateInput',
            'fields' => [
                'name' => [
                    'type' => new NonNull(Types::string()),
                ],
            ],
        ]);
        $config = [
            'fields' => function () use ($unitTypeCreateInput) {
                return [
                    ...UnitTypeMutation::get()
                ];
            }
        ];
        parent::__construct($config);
    }
}