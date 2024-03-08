<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Context\VertuozaContext;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;


class CollaboratorQuery
{
  static function get()
  {
    return [
      'collaborators' => [
        'type' => new NonNull(new ListOfType(Types::get(CollaboratorType::class))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->collaboratorType
          ->collaboratorTypesFindMany
          ->handle()
      ],
      'collaboratorById' => [
        'type' => Types::get(CollaboratorType::class),
        'args' => [
          'id' => new NonNull(Types::string()),
        ],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
        => $context->useCases->collaboratorType
          ->collaboratorTypeById
          ->handle($args['id'])
      ],
    ];
  }
}