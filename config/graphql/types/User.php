<?php

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Entities\CollaboratorEntity;

return new ObjectType([
    'name' => 'User',
    'description' => 'User',
    'entity' => CollaboratorEntity::class,
    'fields' => static fn (): array => [
        'id' => [
            'description' => "Unique identifier of the user",
            'type' => Type::id(),
        ],
        'lastname' => [
            'description' => "Lastname of user",
            'type' => Type::string()
        ],
        'firstname' => [
            'description' => "Firstname of user",
            'type' => new NonNull(Type::string())
        ],
        'fullname' => [
            'description' => 'Concatenation lastname + firstname',
            'type' => new NonNull(Type::string())
        ],
        'username' => [
            'description' => 'Username of user',
            'type' => new NonNull(Type::string())
        ]
    ],
]);