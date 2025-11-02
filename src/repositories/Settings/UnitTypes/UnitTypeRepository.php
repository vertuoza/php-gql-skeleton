<?php

namespace Vertuoza\Repositories\Settings\UnitTypes;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeMapper;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;

use function React\Async\async;

class UnitTypeRepository
{
  protected array $getbyIdsDL;
  private QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

  public function __construct(
    private QueryBuilder $database,
    PromiseAdapterInterface $dataLoaderPromiseAdapter
  ) {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
    $this->getbyIdsDL = [];
  }

  private function fetchByIds(string $tenantId, array $ids)
  {
    return async(function () use ($tenantId, $ids) {
      $query = $this->getQueryBuilder()
        ->where(function ($query) use ($tenantId) {
          $query->where([UnitTypeModel::getTenantColumnName() => $tenantId])
            ->orWhere(UnitTypeModel::getTenantColumnName(), null);
        });

      $query->whereNull('deleted_at');
      $query->whereIn(UnitTypeModel::getPkColumnName(), $ids);

      $entities = $query->get()->mapWithKeys(function ($row) {
        $entity = UnitTypeMapper::modelToEntity(UnitTypeModel::fromStdclass($row));
        return [$entity->id => $entity];
      });

      // Map the IDs to the corresponding entities, preserving the order of IDs.
      return collect($ids)
        ->map(fn ($id) => $entities->get($id))
        ->toArray();
    })();
  }

  protected function getDataloader(string $tenantId): DataLoader
  {
    if (!isset($this->getbyIdsDL[$tenantId])) {

      $dl = new DataLoader(function (array $ids) use ($tenantId) {
        return $this->fetchByIds($tenantId, $ids);
      }, $this->dataLoaderPromiseAdapter);
      $this->getbyIdsDL[$tenantId] = $dl;
    }

    return $this->getbyIdsDL[$tenantId];
  }


  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(UnitTypeModel::getTableName());
  }

  public function getByIds(array $ids, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->loadMany($ids);
  }

  public function getById(string $id, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->load($id);
  }

    /**
     * Check if a UnitType label already exists for a given tenant
     *
     * If tenantId is null, check for all UnitTypes
     * If tenantId is not null, check only for that tenant and globally
     *
     * @param string $name The label to check.
     * @param string|null $tenantId The tenant ID to scope the check.
     * @param string|null $excludeId ID to exclude (useful when updating).
     * @return Promise<bool> Returns true if the label exists, false otherwise.
     */
    public function labelExists(string $name, ?string $tenantId = null, ?string $excludeId = null): Promise
    {
        return async(fn () =>
          $this->getQueryBuilder()
            ->where(UnitTypeModel::getLabelColumnName(), $name)
            ->whereNull('deleted_at')
            ->when($excludeId !== null, fn($query) => $query->where(UnitTypeModel::getPkColumnName(), '!=', $excludeId))
            ->when($tenantId !== null, fn($query) =>
                $query->where(function ($query) use ($tenantId) {
                    $query->where(UnitTypeModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(UnitTypeModel::getTenantColumnName());
                }))

            ->exists()
        )();
    }

  public function findMany(string $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->whereNull('deleted_at')
        ->where(function ($query) use ($tenantId) {
          $query->where(UnitTypeModel::getTenantColumnName(), '=', $tenantId)
            ->orWhereNull(UnitTypeModel::getTenantColumnName());
        })
        ->get()
        ->map(function ($row) {
          return UnitTypeMapper::modelToEntity(UnitTypeModel::fromStdclass($row));
        })
    )();
  }

    /**
     * Insert UnitType and return the ID
     * @param UnitTypeMutationData $data
     * @param string|null $tenantId
     * @return Promise UnitType ID
     */
    public function create(UnitTypeMutationData $data, ?string $tenantId = null): Promise
    {
        return async(function () use ($data, $tenantId) {
            $unitType = UnitTypeMapper::serializeCreate($data, $tenantId);
            $this->getQueryBuilder()->insert($unitType);
            return $unitType['id'];
        })();
    }

  public function update(string $id, UnitTypeMutationData $data)
  {
    $this->getQueryBuilder()
      ->where(UnitTypeModel::getPkColumnName(), $id)
      ->update(UnitTypeMapper::serializeUpdate($data));

    $this->clearCache($id);
  }

  private function clearCache(string $id)
  {
    foreach ($this->getbyIdsDL as $dl) {
      if ($dl->key_exists($id)) {
        $dl->clear($id);
        return;
      }
    }
  }
}
