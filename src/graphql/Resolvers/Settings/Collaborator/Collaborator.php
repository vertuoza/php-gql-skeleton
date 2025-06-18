<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborator;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class Collaborator extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => "Collaborator",
            'description' => "Collaborator type",
            'fields' => static fn (): array => [
                'id' => [
                    'description' => "Unique identifier of the collaborator type",
                    'type' => new NonNull(Types::id()),
                ],
                'name' => [
                    'description' => "Surname of the collaborator type",
                    'type' => new NonNull(Types::string())
                ],
                'firstName' => [
                    'description' => "Name of the collaborator type",
                    'type' => new NonNull(Types::string())
                ],
            ],
        ]);
    }
}