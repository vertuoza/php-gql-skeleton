<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorTypeQuery
{
    static function get()
    {
        return [
            'CollaboratorId' => [
                'type' => Types::get(CollaboratorType::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborators
                    ->findId
                    ->findCollaborator($args['id'], $context)
            ],
            'CollaboratorType' => [
                'type' => new NonNull(new ListOfType(Types::get(CollaboratorType::class))),
                'resolve' => static fn($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborators
                    ->findMany
                    ->handle()
            ],
        ];
    }
}
