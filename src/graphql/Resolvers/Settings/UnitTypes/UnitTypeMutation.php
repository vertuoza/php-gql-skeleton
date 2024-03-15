<?php
namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Entities\Settings\UnitTypeEntity; // Import de l'entité UnitType
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeMutation
{
    static function get()
    {
        return [
            'unitTypeCreate' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'name' => new NonNull(Types::string()),
                ],
                'resolve' => static function ($rootValue, $args, RequestContext $context) {
                    $data = new UnitTypeMutationData();
                    $data->name = $args['name'];
                    
                    // Exécuter la mutation pour créer un nouvel objet UnitType
                    $createdUnitType = $context->useCases->unitType
                        ->unitTypeCreateByName
                        ->handle($data);
                    
                    // Créer une instance de UnitTypeEntity avec les données retournées par la mutation
                    $unitTypeEntity = new UnitTypeEntity();
                    $unitTypeEntity->id = $createdUnitType; // Supposons que l'ID est retourné par la mutation
                    $unitTypeEntity->name = $data->name; // Supposons que le nom est retourné par la mutation
                    $unitTypeEntity->isSystem = false; // Supposons que isSystem est retourné par la mutation
                    
                    // Retourner l'objet UnitTypeEntity avec les nouveaux champs ajoutés
                    return $unitTypeEntity;
                }
            ]
        ];
    }
}
