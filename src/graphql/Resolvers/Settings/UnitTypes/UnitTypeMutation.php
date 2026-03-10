<?php
namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
  public static function get(): array
  {
    $unitTypeCreateInput = new InputObjectType([
      'name'   => 'UnitTypeCreateInput',
      'fields' => [
        'name' => new NonNull(Types::string()),
      ]
    ]);

    return [
      'unitTypeCreate' => [
        'type' => new NonNull(Types::get(UnitType::class)),
        'args' => [
          'input' => new NonNull($unitTypeCreateInput),
        ],
        'resolve' => static fn ($root, $args, RequestContext $context)
          => $context->useCases->unitType->unitTypeCreate->handle($args['input'])
      ],
    ];
  }
}