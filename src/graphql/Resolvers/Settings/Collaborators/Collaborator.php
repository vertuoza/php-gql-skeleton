<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class Collaborator extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'Collaborator',
      'description' => "Collaborator object",
      'fields' => static fn (): array => [
        'id' => [
          'description' => "Unique identifier of the collaborator",
          'type' => Types::id(),
        ],
        'name' => [
          'description' => "Name of the collaborator",
          'type' => Types::string(),
        ],
        'firstName' => [
          'description' => "First name of the collaborator",
          'type' => Types::string(),
        ],
      ],
    ]);
  }
}
