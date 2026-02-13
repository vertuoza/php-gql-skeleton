<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
  private static UnitTypeCreateInput|null $unitTypeCreateInput = null;

  private static function getUnitTypeCreateInput(): UnitTypeCreateInput
  {
    if (self::$unitTypeCreateInput === null) {
      self::$unitTypeCreateInput = new UnitTypeCreateInput();
    }

    return self::$unitTypeCreateInput;
  }

  static function get()
  {
    return [
      'unitTypeCreate' => [
        'type' => new NonNull(Types::get(UnitType::class)),
        'args' => [
          'input' => new NonNull(self::getUnitTypeCreateInput()),
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->unitType
          ->unitTypeCreate
          ->handle($args['input'])
      ],
    ];
  }
}
