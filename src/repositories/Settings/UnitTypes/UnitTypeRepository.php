<?php

namespace Vertuoza\Repositories\Settings\UnitTypes;

use Ramsey\Uuid\Uuid;
use function React\Async\async;
use Vertuoza\Repositories\BaseRepository;

use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeMapper;

class UnitTypeRepository extends BaseRepository
{
  /**
   * Return UnitType model class
   *
   * @return string
   */
  protected function getModelClass(): string
  {
    return UnitTypeModel::class;
  }
  
  /**
   * Return UnitType mapper class
   *
   * @return string
   */
  protected function getMapperClass(): string
  {
    return UnitTypeMapper::class;
  }
  
  /**
   * Override the base filter method to handle the special tenant condition for UnitTypes
   * 
   * @param mixed $query
   * @param string $tenantId
   * 
   * @return mixed modified query
   */
  protected function applyBaseFilters($query, string $tenantId)
  {
    $query->whereNull('deleted_at');
    
    $query->where(function ($q) use ($tenantId) {
      $q->where(UnitTypeModel::getTenantColumnName(), '=', $tenantId)
        ->orWhereNull(UnitTypeModel::getTenantColumnName());
    });
    
    return $query;
  }
  
  /**
   * Return number of UnitType entities with label field non-null
   *
   * @param string $name
   * @param string $tenantId
   * @param string|integer|null|null $excludeId
   * 
   * @return Promise
   */
  public function countUnitTypeWithLabel(string $name, string $tenantId, string|int|null $excludeId = null)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->where('label', $name)
        ->whereNull('deleted_at')
        ->where(function ($query) use ($excludeId) {
          if (isset($excludeId))
            $query->where('id', '!=', $excludeId);
        })
        ->where(function ($query) use ($tenantId) {
          $query->where(UnitTypeModel::getTenantColumnName(), '=', $tenantId)
            ->orWhereNull(UnitTypeModel::getTenantColumnName());
        })
    )();
  }

  // These methods could be in BaseRepository if we consider it handling mutations

  /**
   * Create UnitType
   *
   * @param UnitTypeMutationData $data
   * @param string $tenantId
   *
   * @return integer|string
   */
  public function create(UnitTypeMutationData $data, string $tenantId): int|string
  {
    $newId = Uuid::uuid4()->toString();
    $data = array_merge(['id' => $newId], UnitTypeMapper::serializeCreate($data, $tenantId));
    
    $this->getQueryBuilder()->insert($data);

    return $newId;
  }

  /**
   * Update UnitType
   *
   * @param string $id
   * @param UnitTypeMutationData $data
   * 
   * @return void
   */
  public function update(string $id, UnitTypeMutationData $data)
  {
    $this->getQueryBuilder()
      ->where(UnitTypeModel::getPkColumnName(), $id)
      ->update(UnitTypeMapper::serializeUpdate($data));

    $this->clearCache($id);
  }
}
