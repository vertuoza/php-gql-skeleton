<?php
namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ListOfType;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorQuery
{
  public static function get(): array
  {
    return [
      'collaborators' => [
        'type'    => new NonNull(new ListOfType(new NonNull(Types::get(CollaboratorType::class)))),
        'resolve' => static fn ($root, $args, RequestContext $context)
          => $context->useCases->collaborator->collaboratorFindMany->handle($context)
      ],
      'collaboratorById' => [
        'type' => new NonNull(Types::get(CollaboratorType::class)),
        'args' => ['id' => new NonNull(Types::id())],
        'resolve' => static fn ($root, $args, RequestContext $context)
          => $context->useCases->collaborator->collaboratorById->handle($args['id'], $context)
      ],
    ];
  }
}