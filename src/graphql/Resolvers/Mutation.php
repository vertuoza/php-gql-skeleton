<?php

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeMutation;

class Mutation extends ObjectType
{
  final public function __construct()
  {
    $config = [
      'fields' => static fn (): array => [
        ...UnitTypeMutation::get()
      ]
    ];

    parent::__construct($config);
  }
}