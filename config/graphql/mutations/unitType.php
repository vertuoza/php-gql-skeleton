<?php

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Vertuoza\Context\AppContext;
use Vertuoza\Exceptions\NotAuthorizedException;
use Vertuoza\Exceptions\UnauthorizedTenantException;
use Vertuoza\Kernel;

return [
    'createUnitType' => [
        'type' => fn() => Kernel::getInstance()->getType('UnitType'),
        'args' => [
            'name' => new NonNull(Type::string()),
        ],
        'resolve' => fn($rootValue, $args, AppContext $context) =>
            $context->isLogged()
            ?
                $context->isCollaboratorCompliance() || $context->isAdminCompliance()
                ? $context->useCases->unitType
                    ->unitTypeCreate
                    ->handle(...($args + ['tenantId' => $context->getTenantId()]))
                : throw new UnauthorizedTenantException('You are not a collabotor', $context->getTenantId())
            : throw new NotAuthorizedException()
    ]
];
