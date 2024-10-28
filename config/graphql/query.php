<?php

use GraphQL\Type\Definition\Type;

return [
    'name' => 'Query',
    'fields' => function () {
        return [
            ... include __DIR__ . '/queries/unitType.php',
            ... include __DIR__ . '/queries/user.php',
            ... include __DIR__ . '/queries/tenant.php',
        ];
    }
];
