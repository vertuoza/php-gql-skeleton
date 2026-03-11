<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

/**
 * GraphQL Mutation resolvers for UnitType.
 * Exposes unit type mutations to the GraphQL schema.
 */
class UnitTypeMutation
{
  /**
   * Returns all GraphQL mutation fields related to unit types.
   *
   * @return array<string, array> The GraphQL mutation field definitions
   */
  public static function get(): array
  {
    $unitTypeCreateInput = new InputObjectType([
      'name' => 'UnitTypeCreateInput',
      'fields' => [
        'name' => ['type' => new NonNull(Types::string())],
      ],
    ]);

    return [
      'unitTypeCreate' => [
        'type' => new NonNull(Types::get(UnitType::class)),
        'args' => [
          'input' => new NonNull($unitTypeCreateInput),
        ],
        'resolve' => static function ($rootValue, $args, RequestContext $context): mixed {
          $data = new UnitTypeMutationData();
          $data->name = $args['input']['name'];

          return $context->useCases->unitType
            ->unitTypeCreate
            ->handle($data);
        },
      ],
    ];
  }
}