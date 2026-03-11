<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

/**
 * Repository for Collaborator database operations.
 * Uses DataLoader to batch and cache queries and avoid N+1 problems.
 */
class CollaboratorRepository
{
  protected array $getbyIdsDL;
  private QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

  public function __construct(
    QueryBuilder $database,
    PromiseAdapterInterface $dataLoaderPromiseAdapter
  ) {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
    $this->getbyIdsDL = [];
  }

  /**
   * Returns a query builder scoped to the collaborator table.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(CollaboratorModel::getTableName());
  }

  /**
   * Fetches collaborators by IDs for a given tenant (used by DataLoader for batch loading).
   *
   * @param string $tenantId The tenant ID to scope the query
   * @param array<string> $ids The list of collaborator IDs to fetch
   * @return \React\Promise\Promise<CollaboratorEntity[]>
   */
  private function fetchByIds(string $tenantId, array $ids)
  {
    return async(function () use ($tenantId, $ids) {
      $query = $this->getQueryBuilder()
        ->where(function ($query) use ($tenantId) {
          $query->where(CollaboratorModel::getTenantColumnName(), $tenantId)
            ->orWhere(CollaboratorModel::getTenantColumnName(), null);
        });
      $query->whereNull('deleted_at');
      $query->whereIn(CollaboratorModel::getPkColumnName(), $ids);

      $entities = $query->get()->mapWithKeys(function ($row) {
        $entity = CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
        return [$entity->id => $entity];
      });

      return collect($ids)
        ->map(fn ($id) => $entities->get($id))
        ->toArray();
    })();
  }

  /**
   * Returns or creates a DataLoader instance for the given tenant.
   *
   * @param string $tenantId The tenant ID
   * @return DataLoader
   */
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

  /**
   * Returns a single collaborator by ID using the DataLoader.
   *
   * @param string $id The collaborator ID to fetch
   * @param string $tenantId The tenant ID to scope the query
   * @return Promise<CollaboratorEntity|null>
   */
  public function getById(string $id, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->load($id);
  }

  /**
   * Returns all non-deleted collaborators for the given tenant.
   *
   * @param string $tenantId The tenant ID to scope the query
   * @return \React\Promise\Promise<CollaboratorEntity[]>
   */
  public function findMany(string $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->whereNull('deleted_at')
        ->where(function ($query) use ($tenantId) {
          $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
            ->orWhereNull(CollaboratorModel::getTenantColumnName());
        })
        ->get()
        ->map(fn ($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
    )();
  }
}