<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes\CollaboratorTypeQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitMutationCreateQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeQuery;
use Vertuoza\Api\Graphql\Types;

final class Query extends ObjectType
{
  public function __construct()
  {
    $config = [
      'fields' => function () {
        return [
          'hello' => [
            'type' => Types::string(),
            'resolve' => function ($root, $args) {
              return 'world';
            }
          ],
          ...UnitTypeQuery::get(),
          ...CollaboratorTypeQuery::get(),
        ];
      }
    ];
    parent::__construct($config);
  }
}
