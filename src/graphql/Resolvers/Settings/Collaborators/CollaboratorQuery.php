<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Context\VertuozaContext;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Types\Collaborator;

/**
 * GraphQL resolvers for querying Collaborators.
 *
 *  - collaboratorById => Fetch a single collaborator by ID
 *  - collaborators => Fetch all collaborators available for the current user
 */
class CollaboratorQuery
{
    static function get()
    {
        return [
            'collaboratorById' => [
                'description' => 'Get one Collaborator by ID',
                'type' => Types::get(Collaborator::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator
                    ->collaboratorById
                    ->handle($args['id'])
            ],
            'collaborators' => [
                'description' => 'Get all Collaborators of the system available for the current user',
                'type' => new NonNull(new ListOfType(Types::get(Collaborator::class))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator
                    ->collaboratorFindMany
                    ->handle()
            ],
        ];
    }
}
