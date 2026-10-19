<?php

namespace Vertuoza\Api\Graphql\Resolvers\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorQuery
{
  static function get()
  {
    return [
      'collaborators' => [
        'description' => 'Collaborators available for the current user',
        'type' => new NonNull(new ListOfType(new NonNull(Types::get(Collaborator::class)))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->collaborator
          ->collaboratorsFindMany
          ->handle()
      ],
      'collaboratorById' => [
        'description' => 'A collaborator available for the current user. Fails with NOT_FOUND if it does not exist.',
        'type' => new NonNull(Types::get(Collaborator::class)),
        'args' => [
          'id' => [
            'description' => 'Unique identifier of the collaborator',
            'type' => new NonNull(Types::id()),
          ],
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->collaborator
          ->collaboratorById
          ->handle($args['id'])
      ],
    ];
  }
}
