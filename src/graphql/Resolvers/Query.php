<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators\CollaboratorQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\Hello\HelloQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeQuery;

final class Query extends ObjectType
{
  public function __construct()
  {
    $config = [
      'fields' => function () {
        return [
          ...HelloQuery::get(),
          ...UnitTypeQuery::get(),
          ...CollaboratorQuery::get(),
        ];
      }
    ];
    parent::__construct($config);
  }
}
