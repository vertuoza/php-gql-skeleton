<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInput extends InputObjectType
{
  public function __construct()
  {
    $config = [
      'fields' => [
        'name' => new NonNull(Types::string()),
      ]
    ];
    parent::__construct($config);
  }
}
