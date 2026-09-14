<?php

namespace Vertuoza\Api\Graphql\Resolvers\Collaborators;

use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ListOfType;


class CollaboratorQuery
{
    static function get()
    {
        return [
            'collaboratorById' => [
                'type' => new NonNull(Types::get(Collaborator::class)),
                'args' => [
                    'id' => new NonNull(Types::id())
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context
                    ->useCases
                    ->collaborator
                    ->collaboratorById
                    ->handle($args['id'])
            ],
            'collaborators' => [
                'type' => new NonNull(new ListOfType(new NonNull(Types::get(Collaborator::class)))),                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context
                    ->useCases
                    ->collaborator
                    ->collaboratorsFindMany
                    ->handle()
            ]
        ];
    }
}