<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Collaborators;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

class Collaborator extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collaborator',
            'fields' => [
                'id' => Types::id(),
                'name' => Types::string(),
                'firstName' => Types::string(),
            ],
        ]);
    }
}