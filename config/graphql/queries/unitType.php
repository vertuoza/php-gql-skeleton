<?php

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Vertuoza\Context\AppContext;
use Vertuoza\Exceptions\NotAuthorizedException;
use Vertuoza\Exceptions\UnauthorizedTenantException;
use Vertuoza\Kernel;

return [
    'unitTypeById' => [
        'type' => fn() => Kernel::getInstance()->getType('UnitType'),
        'args' => [
            'id' => new NonNull(Type::string()),
        ],
        'resolve' => static fn ($rootValue, $args, AppContext $context) =>
        $context->isLogged()
            ? $context->isCollaboratorCompliance() || $context->isAdminCompliance()
                ? $context->useCases->unitType
                ->unitTypeById
                ->handle(...($args + ['tenantId' => $context->getTenantId()]))
                : throw new UnauthorizedTenantException('You are not a collaborator of the tenant owner of the unitType.', $context->getTenantId())
            : throw new NotAuthorizedException()
    ],
    'unitTypes' => [
        'type' => new NonNull(new ListOfType(fn() => Kernel::getInstance()->getType('UnitType'))),
        'resolve' => static fn ($rootValue, $args, AppContext $context) =>
            $context->isLogged()
            ?
                $context->isCollaboratorCompliance() || $context->isAdminCompliance()
                ? $context->useCases->unitType
                    ->unitTypeFindMany
                    ->handle($context->getTenantId())
                : throw new UnauthorizedTenantException('You are not a collaborator of the tenant owner of the unitType.', $context->getTenantId())
            : throw new NotAuthorizedException()
    ]
];
