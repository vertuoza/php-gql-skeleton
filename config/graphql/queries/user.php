<?php

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Context\AppContext;
use Vertuoza\Exceptions\NotAuthorizedException;
use Vertuoza\Kernel;

return [
    'users' => [
        'type' => new NonNull(new ListOfType(fn() => Kernel::getInstance()->getType('User'))),
        'resolve' => static fn ($rootValue, $args, AppContext $context) =>
            $context->useCases->user
                ->userFindMany
                ->handle()
    ],
    'login' => [
        'type' => fn() => Kernel::getInstance()->getType('User'),
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->isLogged() ? $context->user : throw new NotAuthorizedException(),
    ],
];
