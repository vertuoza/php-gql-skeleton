<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\InputObjectType;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreate extends InputObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'UnitTypeCreate',
      'description' => 'Create a unit type',
      'fields' => static fn (): array => [
        'name' => [
          'description' => 'Name of the unit',
          'type' => new NonNull(Types::string())
        ],
      ],
    ]);
  }
}