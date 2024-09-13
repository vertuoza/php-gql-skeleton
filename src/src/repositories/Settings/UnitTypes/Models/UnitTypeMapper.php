<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use stdClass;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Entities\Settings\UnitTypeEntity;

class UnitTypeMapper
{
  public static function modelToEntity(UnitTypeModel $dbData): UnitTypeEntity
  {
    $entity = new UnitTypeEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->label;
    $entity->isSystem = $dbData->tenant_id === null;

    return $entity;
  }

  public static function serializeUpdate(UnitTypeMutationData $mutation): array
  {
    return self::serializeMutation($mutation);
  }

  public static function serializeCreate(UnitTypeMutationData $mutation, string $tenantId, string $id): array
  {
    return self::serializeMutation($mutation, $tenantId, $id);
  }

  private static function serializeMutation(UnitTypeMutationData $mutation, string $tenantId = null, string $id = null): array
  {
    $data = [
      'label' => $mutation->name,
    ];

    if ($tenantId) {
      $data[UnitTypeModel::getTenantColumnName()] = $tenantId;
    }

    if ($id) {
      $data[UnitTypeModel::getPkColumnName()] = $id;
    }

    return $data;
  }
}
