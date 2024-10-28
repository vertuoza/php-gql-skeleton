<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Kernel;

return new ObjectType([
    'name' => 'Tenant',
    'description' => 'Tenant',
    'entity' => TenantEntity::class,
    'fields' => static fn (): array => [
        'id' => [
            'description' => "Unique identifier of the user",
            'type' => Type::id(),
        ],
        'name' => [
            'description' => "Name of tenant",
            'type' => Type::string()
        ],
        'owner' => [
            'description' => "Tenant owner",
            'type' => Kernel::getInstance()->getType('User')
        ],
        'collaborators' => [
            'description' => "Tenant collaborators",
            'type' => Type::listOf(Kernel::getInstance()->getType('User'))
        ]
    ],
]);