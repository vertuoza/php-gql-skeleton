<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class CollaboratorType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'CollaboratorType',
            'description' => 'Collaborator"',
            'fields' => static fn(): array => [
                'id' => [
                    'description' => "Unique identifier of the Collaborator",
                    'type' => Types::id(),
                ],
                'name' => [
                    'description' => "Name of the Collaborator",
                    'type' => Types::string()
                ],
                'first_name' => [
                    'description' => "First Name of the Collaborator",
                    'type' => Types::string()
                ],
            ],
        ]);
    }
}
