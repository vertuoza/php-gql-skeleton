<?php

declare(strict_types=1);

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;

/**
 * Definition of collaborator entity fields for GraphQL queries.
 */
class Collaborator extends ObjectType
{
    /** @throws InvariantViolation */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collaborator',
            'description' => 'Collaborator',
            'fields' => [
                'id' => [
                    'description' => 'Unique identifier of the collaborator',
                    'type' => Type::nonNull(Types::id()),
                ],
                'name' => [
                    'description' => 'Name of the collaborator',
                    'type' => Type::nonNull(Types::string())
                ],
                'firstName' => [
                    'description' => 'First name of the collaborator',
                    'type' => Type::nonNull(Types::string())
                ],
            ]
        ]);
    }
}
