<?php

return [
    'name' => 'Mutation',
    'fields' => function () {
        return [
            ... include __DIR__.'/mutations/unitType.php',
            ... include __DIR__.'/mutations/user.php',
            ... include __DIR__.'/mutations/tenant.php',
        ];
    }
];
