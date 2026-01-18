<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;

class CollaboratorQuery
{
    public static function get(): array
    {
        return [
            'collaborators' => [
                'type' => Type::nonNull(Type::listOf(Type::nonNull(Types::get(Collaborator::class)))),
                'resolve' => fn($root, $args, $context) => $context->useCases->collaborator->findMany()
            ],
            'collaboratorById' => [
                'type' => Types::get(Collaborator::class),
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
                'resolve' => fn($root, $args, $context) => $context->useCases->collaborator->findById($args['id'])
            ],
        ];
    }
}