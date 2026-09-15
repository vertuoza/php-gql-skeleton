<?php

namespace Vertuoza\Repositories\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
  protected array $getByIdsDL = [];
  private QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

  public function __construct(private QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
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

      return collect($ids)->map(fn ($id) => $entities->get($id))->toArray();
    })();
  }

  protected function getDataloader(string $tenantId): DataLoader
  {
    if (!isset($this->getByIdsDL[$tenantId])) {
      $this->getByIdsDL[$tenantId] = new DataLoader(
        fn (array $ids) => $this->fetchByIds($tenantId, $ids),
        $this->dataLoaderPromiseAdapter
      );
    }
    return $this->getByIdsDL[$tenantId];
  }

  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(CollaboratorModel::getTableName());
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
        ->get()
        ->map(fn ($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
    )();
  }
}