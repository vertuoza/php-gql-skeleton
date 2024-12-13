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
      'description' => 'Collaborator',
      'fields' => static fn (): array => [
        'id' => [
          'description' => "Unique identifier of the Collaborator",
          'type' => Types::id(),
        ],
        'name' => [
          'description' => "Name of the Collaborator",
          'type' => Types::string()
        ],
        'firstName' => [
          'description' => "First Name of the Collaborator",
          'type' => Types::string()
        ],
      ],
    ]);
  }
}
