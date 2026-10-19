<?php

namespace Vertuoza\Repositories\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Database\QueryBuilder;

use function React\Async\async;

/**
 * Every read is scoped to the tenant of the current user and ignores
 * soft-deleted rows, so a collaborator of another tenant can never be reached,
 * even by guessing its id.
 */
class CollaboratorRepository
{
  /** @var array<string, DataLoader> one dataloader per tenant */
  protected array $getByIdsDL = [];

  public function __construct(
    private QueryBuilder $database,
    protected PromiseAdapterInterface $dataLoaderPromiseAdapter
  ) {
  }

  protected function getQueryBuilder()
  {
    return $this->database->getConnection()->table(CollaboratorModel::getTableName());
  }

  /**
   * Single query for all the requested ids (batched by the dataloader).
   * Returns the entities in the same order as $ids, null when not found.
   */
  private function fetchByIds(string $tenantId, array $ids): PromiseInterface
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

      return collect($ids)
        ->map(fn ($id) => $entities->get($id))
        ->toArray();
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

  public function getById(string $id, string $tenantId): PromiseInterface
  {
    return $this->getDataloader($tenantId)->load($id);
  }

  public function findMany(string $tenantId): PromiseInterface
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
