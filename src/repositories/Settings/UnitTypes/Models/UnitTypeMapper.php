<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use stdClass;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Ramsey\Uuid\Uuid;

class UnitTypeMapper
{
  public static function modelToEntity(UnitTypeModel $dbData): UnitTypeEntity
  {
    $entity = new UnitTypeEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->label;
    $entity->isSystem = is_null($dbData->tenant_id);

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
      'id'=> Uuid::uuid4()->toString(),
      'label' => $mutation->name,
      UnitTypeModel::getTenantColumnName() => null
    ];

    if ($tenantId) {
      $data[UnitTypeModel::getTenantColumnName()] = $tenantId;
    }
    return $data;
  }
}
