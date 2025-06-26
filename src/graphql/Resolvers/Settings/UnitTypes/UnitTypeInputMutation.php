<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeInputMutation extends InputObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'UnitTypeInputMutation',
      'description' => 'Unit type input for mutation',
      'fields' => static fn(): array => [
        'name' => [
          'description' => "Name of the unit type",
          'type' => new NonNull(Types::string()),
        ],
      ],
    ]);
  }
}
