<?php

declare(strict_types=1);

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;

/**
 * Definition of queries for collaborator entities.
 * @phpstan-import-type UnnamedFieldDefinitionConfig from FieldDefinition
 */
class CollaboratorQuery
{
    /**
     * @return iterable<UnnamedFieldDefinitionConfig>
     * @throws InvariantViolation
     */
    public static function get(): iterable
    {
        return [
            'collaboratorById' => [
                // The brief requested the return type to be non-nullable, but the actual implementation
                // needs to return a nullable type, as the collaborator might not exist.
                'type' => Types::get(Collaborator::class),
                'args' => [
                    'id' => Type::nonNull(Types::string()),
                ],
                // TODO: Implement
                'resolve' => static fn () => [
                    'id' => '1',
                    'name' => 'John Doe',
                    'firstName' => 'John',
                ],
            ],
            'collaborators' => [
                'type' => Type::nonNull(Type::listOf(Type::nonNull(Types::get(Collaborator::class)))),
                // TODO: Implement
                'resolve' => static fn () => [],
            ],
        ];
    }
}
