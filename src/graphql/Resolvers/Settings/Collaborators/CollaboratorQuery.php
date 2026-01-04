<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;



use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Context\VertuozaContext;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Libs\Logger\ApplicationLogger;
use Vertuoza\Libs\Logger\LogContext;

class CollaboratorQuery
{
    static function get()
    {
        ApplicationLogger::getInstance()->info("query ", new LogContext(null, null));

        return [
            'collaborators' => [
                'type' => new NonNull(new ListOfType(Types::get(Collaborator::class))),
                'resolve' => static fn($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator_use_cases
                    ->collaborators_find_many_use_case
                    ->handle($context)
            ],
            'collaboratorById' => [
                'type' => Types::get(Collaborator::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn($rootValue, $args, RequestContext $context)
                => $context->useCases->collaborator_use_cases
                    ->collaborator_by_id_use_case
                    ->handle($args['id'], $context)
            ],
        ];
    }
}
