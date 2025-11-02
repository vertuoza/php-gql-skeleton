<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use Ramsey\Uuid\Uuid;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Entities\Settings\UnitTypeEntity;

class UnitTypeMapper
{

  /**
   * Convert a UnitTypeModel (database representation)
   * into a UnitTypeEntity (business representation).
   *
   * @param UnitTypeModel $dbData The database model to convert
   * @return UnitTypeEntity The mapped entity
   */
  public static function modelToEntity(UnitTypeModel $dbData): UnitTypeEntity
  {
    $entity = new UnitTypeEntity();
    $entity->id = $dbData->id;
    $entity->name = $dbData->label;
    $entity->isSystem = $dbData->tenant_id === null;

    return $entity;
  }

    /**
     * Prepare data for updating a UnitType in the database.
     *
     * @param UnitTypeMutationData $mutation The mutation data
     * @return array Associative array for the update query
     */
  public static function serializeUpdate(UnitTypeMutationData $mutation): array
  {
    return [
        UnitTypeModel::getLabelColumnName() => $mutation->name,
    ];
  }

    /**
     * Prepare data for creating a new UnitType in the database.
     *
     * @param UnitTypeMutationData $mutation The mutation data
     * @param string|null $tenantId Optional tenant ID
     * @return array Associative array for the insert query
     */
  public static function serializeCreate(UnitTypeMutationData $mutation, string $tenantId = null): array
  {
      return [
          // Generates id before inserting the record because the id is not returned by the database
          UnitTypeModel::getPkColumnName() => Uuid::uuid4()->toString(),
          UnitTypeModel::getLabelColumnName() => $mutation->name,
          UnitTypeModel::getTenantColumnName() => $tenantId,
      ];
  }
}
