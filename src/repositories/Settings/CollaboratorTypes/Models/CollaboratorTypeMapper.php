<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use stdClass;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeModel;
// use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeMutationData;
use Vertuoza\Entities\Settings\CollaboratorTypeEntity;

class CollaboratorTypeMapper
{
  public static function modelToEntity(CollaboratorTypeModel $dbData): CollaboratorTypeEntity
  {
    $entity = new CollaboratorTypeEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;
    return $entity;
  }

//   public static function serializeUpdate(UnitTypeMutationData $mutation): array
//   {
//     return self::serializeMutation($mutation);
//   }

//   public static function serializeCreate(UnitTypeMutationData $mutation, string $tenantId): array
//   {
//     return self::serializeMutation($mutation, $tenantId);
//   }

//   private static function serializeMutation(UnitTypeMutationData $mutation, string $tenantId = null): array
//   {
//     $data = [
//       'label' => $mutation->name,
//     ];

//     if ($tenantId) {
//       $data[CollaboratorTypeModel::getTenantColumnName()] = $tenantId;
//     }
//     return $data;
//   }
}
