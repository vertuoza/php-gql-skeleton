<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use Ramsey\Uuid\Uuid;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Entities\Settings\UnitTypeEntity;

class UnitTypeMapper
{
  public static function modelToEntity(UnitTypeModel $dbData): UnitTypeEntity
  {
    $entity = new UnitTypeEntity();
    $entity->id = $dbData->id;
    $entity->name = $dbData->label;
    $entity->isSystem = $dbData->tenant_id === null;

    return $entity;
  }

  public static function serializeUpdate(UnitTypeMutationData $mutation): array
  {
    return [
        UnitTypeModel::getLabelColumnName() => $mutation->name,
    ];
  }

  public static function serializeCreate(UnitTypeMutationData $mutation, string $tenantId = null): array
  {
      return [
          UnitTypeModel::getPkColumnName() => Uuid::uuid4()->toString(),
          UnitTypeModel::getLabelColumnName() => $mutation->name,
          UnitTypeModel::getTenantColumnName() => $tenantId,
      ];
  }
}
