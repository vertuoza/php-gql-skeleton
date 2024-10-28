<?php

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Vertuoza\Context\AppContext;
use Vertuoza\Exceptions\NotAuthorizedException;
use Vertuoza\Kernel;

return [
    'subscribe' => [
        'type' => fn() => Kernel::getInstance()->getType('User'),
        'args' => [
            'username' => new NonNull(Type::string()),
            'password' => new NonNull(Type::string()),
            'lastname' => new NonNull(Type::string()),
            'firstname' => new NonNull(Type::string())
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            !$context->isLogged()
            ? $context->useCases->user
                ->subscribe
                ->handle(...$args)
            : throw new NotAuthorizedException()

    ]
];
