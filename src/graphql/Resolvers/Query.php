<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Organization\Collaborators\CollaboratorQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeQuery;

final class Query extends ObjectType
{
  public function __construct()
  {
    $config = [
      'fields' => function () {
        return [
          ...UnitTypeQuery::get(),
          ...CollaboratorQuery::get()
        ];
      }
    ];
    parent::__construct($config);
  }
}
