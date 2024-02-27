<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitMutationCreateQuery;
use Vertuoza\Api\Graphql\Types;

final class Mutation extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'mutation',
            'fields' => function () {
                return [
                    ...UnitMutationCreateQuery::get()
                ];
            }
        ];
        parent::__construct($config);
    }
}