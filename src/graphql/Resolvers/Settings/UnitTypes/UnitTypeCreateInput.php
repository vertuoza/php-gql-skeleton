<?php

declare(strict_types=1);

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

/** Definition of params for the unitTypeCreate mutation */
class UnitTypeCreateInput extends InputObjectType
{
    /** @throws InvariantViolation */
    public function __construct()
    {
        parent::__construct([
            'name' => 'UnitTypeCreateInput',
            'description' => 'Input parameters for the unitTypeCreate mutation',
            'fields' => [
                'name' => [
                    'description' => 'Name of the collaborator',
                    'type' => Type::nonNull(Types::string())
                ],
            ],
            'parseValue' => static fn (array $values) => new UnitTypeMutationData($values['name']),
        ]);
    }
}
