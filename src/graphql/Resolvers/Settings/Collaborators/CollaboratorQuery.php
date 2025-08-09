<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorQuery
{
  static function get()
  {
    return [
      'collaboratorById' => [
        'description' => 'Get the collaborator from its ID',
        'type' => Types::get(Collaborator::class),
        'args' => [
          'id' => new NonNull(Types::string()),
        ],
        'resolve' => static function ($rootValue, $args, RequestContext $context) {
          return $context->useCases->collaborator
            ->collaboratorById
            ->handle($args['id']);
        },
      ],
      'collaborators' => [
        'description' => 'Get all the collaborators of the current tenant',
        'type' => new NonNull(new ListOfType(Types::get(Collaborator::class))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context) => $context->useCases->collaborator
          ->collaboratorsFindMany
          ->handle(),
      ],
    ];
  }
}
