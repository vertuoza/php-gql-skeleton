<?php

namespace Vertuoza\Api\Graphql\Resolvers\Collaborators;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class Collaborator extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collaborator',
            'description' => 'Collaborator entity',
            'fields' => static fn (): array => [
                'id' => [
                    'description' => "Unique identifier of the collaborator",
                    'type' => Types::id(),
                ],
                'name' => [
                    'description' => "Name of the collaborator",
                    'type' => Types::string()
                ],
                'first_name' => [
                    'description' => "First name of the collaborator",
                    'type' => Types::string()
                ],
            ],
        ]);
    }
}