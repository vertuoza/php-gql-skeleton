<?php
namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class CollaboratorType extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'Collaborator',
      'fields' => static fn (): array => [
        'id' => [
          'description' => 'Unique identifier of the collaborator',
          'type' => new NonNull(Types::id()),
        ],
        'name' => [
          'description' => 'Last name of the collaborator',
          'type' => new NonNull(Types::string()),
        ],
        'firstName' => [
          'description' => 'First name of the collaborator',
          'type' => new NonNull(Types::string()),
        ],
      ],
    ]);
  }
}