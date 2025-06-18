<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborator;

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
                'description' => "Finds a collaborator using its unique identifier.",
                'type' => new NonNull(Types::get(Collaborator::class)),
                'args' => [
                    'id' => new NonNull(Types::id())
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                    => $context->useCases->collaborator->findById->handle($args['id'])
            ],
            'collaborators' => [
                'description' => "Finds all collaborators of the current user (tenant identifier).",
                'type' => new NonNull(new ListOfType(new NonNull(Types::get(Collaborator::class)))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                    => $context->useCases->collaborator->findMany->handle()
            ],

        ];
    }
}