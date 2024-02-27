<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class CollaboratorType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'CollaboratorType',
            'description' => 'Collaborator type',
            'fields' => static fn (): array => [
                'id' => [
                    'description' => "Unique identifier of the collaborator type",
                    'type' => Types::id(),
                ],
                'name' => [
                    'description' => "last name of collaborator",
                    'type' => Types::string()
                ],
                'first_name' => [
                    'description' => "firstname of collaborator",
                    'type' => new NonNull(Types::string())
                ],
                'isSystem' => [
                    'description' => "To know if the collaborator type has been created by the user or is a system unit type of Vertuoza",
                    'type' => new \GraphQL\Type\Definition\NonNull(Types::boolean())
                ],
            ],
        ]);
    }
}