<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;


class UnitTypeQuery
{
  static function get()
  {
    return [
      'unitTypeById' => [
        'description' => 'Get the unit type from its ID',
        'type' => Types::get(UnitType::class),
        'args' => [
          'description' => 'ID of the unit type to find',
          'id' => new NonNull(Types::string()),
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context) => $context->useCases->unitType
          ->unitTypeById
          ->handle($args['id'], $context)
      ],
      'unitTypes' => [
        'description' => 'Get all the unit types of the current tenant',
        'type' => new NonNull(new ListOfType(Types::get(UnitType::class))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context) => $context->useCases->unitType
          ->unitTypesFindMany
          ->handle($context)
      ],
    ];
  }
}
