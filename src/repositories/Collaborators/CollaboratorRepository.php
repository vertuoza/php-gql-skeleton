<?php

namespace Vertuoza\Repositories\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Database\QueryBuilder;

use function React\Async\async;

class CollaboratorRepository
{
  private QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;
  protected array $getbyIdsDL;

  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
    $this->getbyIdsDL = [];
  }

  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(CollaboratorModel::getTableName());
  }

  private function fetchByIds(string $tenantId, array $ids)
  {
    return async(function () use ($tenantId, $ids) {
      $entities = $this->getQueryBuilder()
        ->where(CollaboratorModel::getTenantColumnName(), $tenantId)
        ->whereNull('deleted_at')
        ->whereIn(CollaboratorModel::getPkColumnName(), $ids)
        ->get()
        ->mapWithKeys(function ($row) {
          $entity = CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
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

  public function getById(string $id, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->load($id);
  }

  public function findMany(string $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->where(CollaboratorModel::getTenantColumnName(), $tenantId)
        ->whereNull('deleted_at')
        ->orderBy('name')
        ->orderBy('first_name')
        ->get()
        ->map(fn ($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
    )();
  }
}
