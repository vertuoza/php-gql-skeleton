<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeQuery;
use Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators\CollaboratorQuery;

final class Query extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'Query',
      'fields' => function () {
        return [
          ...UnitTypeQuery::get(),
          ...CollaboratorQuery::get()
        ];
      }
    ]);
  }
}
