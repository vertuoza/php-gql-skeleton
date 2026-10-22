<?php

namespace Vertuoza\Repositories\Collaborators;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Database\QueryBuilder;

use function React\Async\async;

class CollaboratorRepository
{
  private QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
  }

  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(CollaboratorModel::getTableName());
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
