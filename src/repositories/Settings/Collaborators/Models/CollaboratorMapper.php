<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class CollaboratorMapper
{
  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id;
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->firstName;
    return $entity;
  }

  public static function serializeUpdate(UnitTypeMutationData $mutation): array
  {
    return self::serializeMutation($mutation);
  }

  public static function serializeCreate(UnitTypeMutationData $mutation, string $tenantId): array
  {
    return self::serializeMutation($mutation, $tenantId);
  }

  private static function serializeMutation(UnitTypeMutationData $mutation, string $tenantId = null): array
  {
    $data = [
      'label' => $mutation->name,
    ];

    if ($tenantId) {
      $data[UnitTypeModel::getTenantColumnName()] = $tenantId;
    }
    return $data;
  }
}
