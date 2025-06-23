<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\InputObjectType;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
    public static function get()
    {
        $unitTypeCreateInput = new InputObjectType([
            'name' => 'UnitTypeCreateInput',
            'fields' => [
                'name' => [
                    'type' => new NonNull(Types::string()),
                ],
            ],
        ]);

        return [
            'createUnitType' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'input' => [
                        'type' => $unitTypeCreateInput,
                    ],
                ],
                'resolve' => function ($root, $args, RequestContext $context) {
                    $name = trim($args['input']['name']);
                    if ($name === '') {
                        throw new \Exception("Le nom ne peut pas être vide.");
                    }
                    $unitTypeRepo = $context->repositories->unitType;
                    $tenantId = $context->userContext->getTenantId();
                    $mutationData = new UnitTypeMutationData();
                    $mutationData->name = $args['input']['name'];
                    $newId = $unitTypeRepo->create($mutationData, $tenantId);
                    return $unitTypeRepo->getById($newId, $tenantId);
                }
            ],
        ];
    }
}