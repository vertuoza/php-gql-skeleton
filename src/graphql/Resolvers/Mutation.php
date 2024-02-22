<?php

declare(strict_types=1);

namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeMutation;

/** Service providing configuration of all available mutations */
final class Mutation extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => static fn () => [
                ...UnitTypeMutation::get(),
            ],
        ]);
    }
}
