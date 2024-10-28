<?php

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Vertuoza\Context\AppContext;
use Vertuoza\Exceptions\NotAuthorizedException;
use Vertuoza\Kernel;

return [
    'createTenant' => [
        'type' => fn() => Kernel::getInstance()->getType('Tenant'),
        'args' => [
            'name' => new NonNull(Type::string()),
            'collaborators' => Type::listOf(Type::id())
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->isLogged()
            ? $context->useCases->tenant
                ->create
                ->handle(...($args + ['owner' => $context->user]))
            : throw new NotAuthorizedException()
    ],
    'deleteTenant' => [
        'type' => fn() => Kernel::getInstance()->getType('Tenant'),
        'args' => [
            'id' => new NonNull(Type::id())
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->isLogged()
            ? $context->useCases->tenant
                ->delete
                ->handle(...($args + ['owner' => $context->user]))
            : throw new NotAuthorizedException()
    ],
    'updateTenant' => [
        'type' => fn() => Kernel::getInstance()->getType('Tenant'),
        'args' => [
            'id' => new NonNull(Type::id()),
            'name' => Type::string(),
            'collaborators' => Type::listOf(Type::id())
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->isLogged()
            ? $context->useCases->tenant
                ->update
                ->handle(...($args + ['owner' => $context->user]))
            : throw new NotAuthorizedException()
    ]
];
