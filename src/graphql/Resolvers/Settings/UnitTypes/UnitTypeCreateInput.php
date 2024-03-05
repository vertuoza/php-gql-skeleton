<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;


class UnitTypeCreateInput extends InputObjectType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'UnitTypeCreateInput',
			'description' => 'Input parameters for the unitTypeCreate mutation',
			'fields' => static fn(): array => [
				'name' => [
					'description' => 'Name of the collaborator',
					'type' => Type::nonNull(Types::string())
				],
			],
			'parseValue' => static fn (array $values) => new UnitTypeMutationData($values['name']),
		]);
    }
}
