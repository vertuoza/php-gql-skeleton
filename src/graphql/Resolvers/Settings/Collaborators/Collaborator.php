<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Types;

/**
 * GraphQL Object Type for Collaborator.
 * Exposes the CollaboratorEntity fields to the GraphQL schema.
 */
class Collaborator extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'fields' => fn () => [
        'id' => [
          'type' => new NonNull(Types::id()),
          'resolve' => fn ($collaborator) => $collaborator->id,
        ],
        'name' => [
          'type' => new NonNull(Types::string()),
          'resolve' => fn ($collaborator) => $collaborator->name,
        ],
        'firstName' => [
          'type' => new NonNull(Types::string()),
          'resolve' => fn ($collaborator) => $collaborator->firstName,
        ],
      ],
    ]);
  }
}