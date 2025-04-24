<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreateInputType;

class UnitTypeMutation extends ObjectType
{
  static function get()
  {
    return [
      'unitTypeCreate' => [
        'type' => Type::nonNull(Types::get(UnitType::class)),
        'args' => [
          'input' => Type::nonNull(Types::get(UnitTypeCreateInputType::class)),
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->unitType
          ->unitTypeCreateUseCase
          ->handle($args['input']['name'], $context)
      ]
    ];
  }
}
