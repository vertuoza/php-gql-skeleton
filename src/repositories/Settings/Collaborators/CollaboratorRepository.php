<?php
namespace Vertuoza\Repositories\Settings\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
  protected array $getbyIdsDL = [];
  private QueryBuilder $db;

  public function __construct(
    QueryBuilder $database,
    protected PromiseAdapterInterface $dataLoaderPromiseAdapter
  ) {
    $this->db = $database;
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
        ->whereIn(CollaboratorModel::getPkColumnName(), $ids)
        ->get()
        ->mapWithKeys(function ($row) {
          $entity = CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
          return [$entity->id => $entity];
        });

      return collect($ids)
        ->map(fn ($id) => $entities->get($id))
        ->toArray();
    })();
  }

  protected function getDataloader(string $tenantId): DataLoader
  {
    if (!isset($this->getbyIdsDL[$tenantId])) {
      $this->getbyIdsDL[$tenantId] = new DataLoader(
        fn (array $ids) => $this->fetchByIds($tenantId, $ids),
        $this->dataLoaderPromiseAdapter
      );
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
        ->get()
        ->map(fn ($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
    )();
  }
}