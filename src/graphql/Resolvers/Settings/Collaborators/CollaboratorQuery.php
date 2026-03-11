<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

/**
 * GraphQL Query resolvers for Collaborator.
 * Exposes collaborator queries to the GraphQL schema.
 */
class CollaboratorQuery
{
  /**
   * Returns all GraphQL query fields related to collaborators.
   *
   * @return array<string, array> The GraphQL query field definitions
   */
  public static function get(): array
  {
    return [
      'collaborators' => [
        'type' => new NonNull(new ListOfType(new NonNull(Types::get(Collaborator::class)))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
          => $context->useCases->collaborator
            ->collaboratorsFindMany
            ->handle(),
      ],
      'collaboratorById' => [
        'type' => new NonNull(Types::get(Collaborator::class)),
        'args' => [
          'id' => new NonNull(Types::id()),
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
          => $context->useCases->collaborator
            ->collaboratorById
            ->handle($args['id']),
      ],
    ];
  }
}