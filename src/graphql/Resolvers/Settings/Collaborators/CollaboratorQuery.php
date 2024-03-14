<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;


class Collaborator
{
    static function get()
    {
        return [
            'collaborators' => [
                'type' => new NonNull(new ListOfType(Types::get(Collaborator::class))),
                'resolve' => static fn ($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator
                    ->collaboratorsFindMany
                    ->handle($context)
            ],
        ];
    }
}
