<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

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
            'collaboratorById' => [
                'type' => new NonNull(Types::get(Collaborator::class)),
                'args' => [
                    'id' => new NonNull(Types::id()),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator
                    ->collaboratorById
                    ->handle($args['id'], $context)
            ],
            'collaborators' => [
                'type' => new NonNull(new ListOfType(new NonNull(Types::get(Collaborator::class)))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator
                    ->collaboratorFindMany
                    ->handle($context)
            ],
        ];
    }
}
