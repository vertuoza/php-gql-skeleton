<?php

namespace Vertuoza\Repositories\Settings\UnitTypes;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeMapper;
use Vertuoza\Repositories\Settings\UnitTypes\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

// TODO: Move this into Lib or use an external library
function generateUUIDv4($data = null)
{
  // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
  $data = $data ?? random_bytes(16);
  assert(strlen($data) == 16);

  // Set version to 0100
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  // Set bits 6-7 to 10
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  // Output the 36 character UUID.
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

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

  public function create(UnitTypeMutationData $data, string $tenantId): int|string
  {
    $entity = UnitTypeMapper::serializeCreate($data, $tenantId);

    $uuid = generateUUIDv4(); 
    $entity['id'] = $uuid;

    $this->getQueryBuilder()->insert($entity);
    error_log("Created unit type: " . print_r($entity, true));

    return $entity['id'];
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
