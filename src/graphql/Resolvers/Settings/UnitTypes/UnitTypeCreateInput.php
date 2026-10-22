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
      'description' => "Data required to create a unit type",
      'fields' => static fn (): array => [
        'name' => [
          'description' => "Name of the unit type, unique among the available unit types (255 characters max)",
          'type' => new NonNull(Types::string()),
        ],
      ],
    ]);
  }
}
