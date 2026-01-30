<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
  static function get()
  {
    return [
      'unitTypeCreate' => [
        'type' => new NonNull(Types::get(UnitType::class)),
        'args' => [
          'input' => new NonNull(Types::get(UnitTypeCreateInput::class)),
        ],
        'resolve' => static function ($rootValue, $args, RequestContext $context) {
          $data = new UnitTypeMutationData();
          $data->name = $args['input']['name'];

          return $context->useCases->unitType
            ->unitTypeCreate
            ->handle($data);
        }
      ],
    ];
  }
}
