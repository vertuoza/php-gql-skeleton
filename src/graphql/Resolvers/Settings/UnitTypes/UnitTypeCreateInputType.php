<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

class UnitTypeCreateInputType extends InputObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'UnitTypeCreateInput',
      'description' => 'UnitType create input type',
      'fields' => static fn (): array => [
        'name' => [
          'description' => "Unit Type name",
          'type' => new NonNull(Types::string())
        ]
      ],
    ]);
  }
}
