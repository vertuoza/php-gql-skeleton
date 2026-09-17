<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInput extends InputObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'UnitTypeCreateInput',
      'description' => 'Data required to create a unit type',
      'fields' => [
        'name' => [
          'description' => 'Name of the unit type',
          'type' => new NonNull(Types::string())
        ]
      ],
    ]);
  }
}
