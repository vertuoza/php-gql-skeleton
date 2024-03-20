<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
  static function get()
  {
    return [
      'unitTypeCreate' => [
        'type' => Types::get(UnitType::class),
        'args' => [
          'name' => new NonNull(Types::string()),
        ],
        'resolve' => static function ($rootValue, $args, RequestContext $context) {
          $data = new UnitTypeMutationData();
          $data->name = $args['name'];

          $createdUnitType = $context->useCases->unitType
            ->unitTypeCreateByName
            ->handle($data);

          error_log("Created unit type: " . print_r($createdUnitType, true));

          $entity = new UnitTypeEntity();
          $entity->id = $createdUnitType;
          $entity->name = $data->name;
          $entity->isSystem = false;

          return $entity;
        }
      ]
    ];
  }
}
