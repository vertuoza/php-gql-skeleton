<?php

declare(strict_types=1);

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

/**
 * Definition of mutations for unit type entities.
 * @phpstan-import-type UnnamedFieldDefinitionConfig from FieldDefinition
 */
class UnitTypeMutation
{
    /** @return iterable<UnnamedFieldDefinitionConfig> */
    public static function get(): iterable
    {
        return [
            'unitTypeCreate' => [
                'type' => Type::nonNull(Types::get(UnitType::class)),
                'args' => [
                    'input' => Type::nonNull(Types::get(UnitTypeCreateInput::class)),
                ],
                'resolve' => static fn (mixed $rootValue, array $args, RequestContext $context) =>
                    $context->useCases->unitType->unitTypeCreate->handle($args['input']),
            ],
        ];
    }
}
