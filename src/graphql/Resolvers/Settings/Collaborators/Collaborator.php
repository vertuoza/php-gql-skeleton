<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use Vertuoza\Api\Graphql\Types;
use GraphQL\Type\Definition\ObjectType;

class Collaborator extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collaborator',
            'description' => 'Collaborator',
            'fields' => static fn (): array => [
                    'id' => [
                        'description' => "Unique identifier of the collaborator",
                        'type' => Types::id()
                    ],
                    'name' => [
                        'description' => "Name of the collaborator",
                        'type' => Types::string()
                    ],
                    'firstName' => [
                        'description' => "Firstname of the collaborator",
                        'type' => Types::string()
                    ],
                ],
        ]);
    }
}
