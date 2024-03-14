<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use Vertuoza\Api\Graphql\Types;

class Collaborator extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collaborator',
            'description' => 'Collaborator',
            'fields' => static function (): array {
                return [
                    'id' => [
                        'description' => "Unique identifier of the collaborator",
                        'type' => Types::id(),
                    ],
                    'name' => [
                        'description' => "Name of the collaborator",
                        'type' => Types::nonNullString(), // New method to prevent nullable string
                    ],
                    'firstName' => [
                        'description' => "Firstname of the collaborator",
                        'type' => Types::nonNullString(), // Method to prevent nullable string
                    ],
                ];
            },
        ]);
    }
}
