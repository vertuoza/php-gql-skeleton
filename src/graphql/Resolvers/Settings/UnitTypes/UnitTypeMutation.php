<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
  static function get(): array
  {
    return [
      'unitTypeCreate' => [
        'description' => 'Create a new unit type from a name',
        'type' => Types::get(UnitType::class),
        'args' => [
          'description' => 'Name of the new unit type',
          'name' => new NonNull(Types::string()),
        ],
        'resolve' => static function ($rootValue, $args, RequestContext $context) {
          $data = new UnitTypeMutationData();
          $data->name = $args['name'];

          $unitTypeId = $context->useCases->unitType->createUnitType->handle($data);

          $unitTypeEntity = new UnitTypeEntity();
          $unitTypeEntity->id = $unitTypeId;
          $unitTypeEntity->name = $data->name;
          $unitTypeEntity->isSystem = $context->userContext->getTenantId() === null;
          return $unitTypeEntity;
        }
      ]
    ];
  }
}
