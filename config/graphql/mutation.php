<?php

return [
    'name' => 'Mutation',
    'fields' => function () {
        return [
            ... include __DIR__.'/mutations/unitType.php'
        ];
    }
];
