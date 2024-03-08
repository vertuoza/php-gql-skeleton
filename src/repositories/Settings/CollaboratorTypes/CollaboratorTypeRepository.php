<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeMapper;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeModel;
// Todo
// use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeMutationData;

use function React\Async\async;

class CollaboratorTypeRepository
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
    // throw new Exception("Function not implemented : CollaboratorTypeRepository::fetchByIds");

    return async(function () use ($tenantId, $ids) {
      $query = $this->getQueryBuilder()
        ->where(function ($query) use ($tenantId) {
          $query->where([CollaboratorTypeModel::getTenantColumnName() => $tenantId])
            ->orWhere(CollaboratorTypeModel::getTenantColumnName(), null);
        });
      $query->whereNull('deleted_at');
      $query->whereIn(CollaboratorTypeModel::getPkColumnName(), $ids);

      $entities = $query->get()->mapWithKeys(function ($row) {
        $entity = CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
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
    return $this->db->getConnection()->table(CollaboratorTypeModel::getTableName());
  }

  public function getByIds(array $ids, string $tenantId): Promise
  {
    throw new Exception("Function not implemented : CollaboratorTypeRepository::getByIds");

    // return $this->getDataloader($tenantId)->loadMany($ids);
  }

  public function getById(string $id, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->load($id);
  }

  public function countUnitTypeWithLabel(string $name, string $tenantId, string|int|null $excludeId = null)
  {
    throw new Exception("Function not implemented : CollaboratorTypeRepository::countUnitTypeWithLabel");

    // return async(
    //   fn () => $this->getQueryBuilder()
    //     ->where('label', $name)
    //     ->whereNull('deleted_at')
    //     ->where(function ($query) use ($excludeId) {
    //       if (isset($excludeId))
    //         $query->where('id', '!=', $excludeId);
    //     })
    //     ->where(function ($query) use ($tenantId) {
    //       $query->where(CollaboratorTypeModel::getTenantColumnName(), '=', $tenantId)
    //         ->orWhereNull(CollaboratorTypeModel::getTenantColumnName());
    //     })
    // )();
  }

  public function findMany(string $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->whereNull('deleted_at')
        ->where(function ($query) use ($tenantId) {
          $query->where(CollaboratorTypeModel::getTenantColumnName(), '=', $tenantId)
            ->orWhereNull(CollaboratorTypeModel::getTenantColumnName());
        })
        ->get()
        ->map(function ($row) {
          return CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
        })
    )();
  }

  public function create(UnitTypeMutationData $data, string $tenantId): int|string
  {
    throw new Exception("Function not implemented : CollaboratorTypeRepository::create");

    // $newId = $this->getQueryBuilder()->insertGetId(
    //   CollaboratorTypeMapper::serializeCreate($data, $tenantId)
    // );
    // return $newId;
  }

  public function update(string $id, UnitTypeMutationData $data)
  {
    throw new Exception("Function not implemented : CollaboratorTypeRepository::update");

    // $this->getQueryBuilder()
    //   ->where(CollaboratorTypeModel::getPkColumnName(), $id)
    //   ->update(CollaboratorTypeMapper::serializeUpdate($data));

    // $this->clearCache($id);
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
