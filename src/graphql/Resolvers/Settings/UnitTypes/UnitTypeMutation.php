<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;


class UnitTypeMutation
{
  static function get()
  {
    return [
      'unitTypeCreate' => [
        'type' => new NonNull(Types::get(UnitType::class)),
        'args' => [
          'data' => new NonNull(Types::get(UnitTypeCreate::class)),
        ],
        'resolve' => static fn($rootValue, $args, RequestContext $context)
          => $context->useCases->unitType
            ->unitTypeCreate
            ->handle($args['data'])
      ],
    ];
  }
}