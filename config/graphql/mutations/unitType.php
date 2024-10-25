<?php

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Vertuoza\Kernel;
use Vertuoza\Middleware\AppContext;

return [
    'createUnitType' => [
        'type' => fn() => Kernel::getInstance()->getType('UnitType'),
        'args' => [
            'name' => new NonNull(Type::string()),
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->useCases->unitType
                ->unitTypeCreate
                ->handle(...$args)
    ]
];
