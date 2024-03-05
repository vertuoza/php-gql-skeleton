<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
	public static function get(): iterable
	{
		return [
			'unitTypeCreate' => [
				'type' => Type::nonNull(Types::get(UnitType::class)),
				'args' => [
					'input' => Type::nonNull(Types::get(UnitTypeCreateInput::class)),
				],
				'resolve' => static fn(mixed $rootValue, array $args, RequestContext $context) =>
					$context->useCases->unitType->unitTypeCreate->handle($args['input']),
			],
		];
	}
}