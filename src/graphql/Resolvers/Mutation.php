<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreate;

final class Mutation extends ObjectType
{
	public function __construct()
	{
		$config = [
			'fields' => function ()
			{
				return [
					...UnitTypeCreate::get()
				];
			}
		];
		parent::__construct($config);
	}
}