<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class CollaboratorType extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'CollaboratorType',
      'description' => 'Collaborator type',
      'fields' => static fn (): array => [
        'id' => [
          'description' => "Unique identifier of the collaborator type",
          'type' => Types::id()
        ],
        'name' => [
          'description' => "Name of the collaborator type",
          'type' => Types::string()
        ],
        'firstName' => [
          'description' => "FirstName of the collaborator type",
          'type' => Types::string()
        ],
      ],
    ]);
  }
}
