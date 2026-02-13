<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
  private QueryBuilder $db;

  public function __construct(QueryBuilder $database)
  {
    $this->db = $database;
  }

  protected function getQueryBuilder()
  {
    return $this->db->getConnection()->table(CollaboratorModel::getTableName());
  }

  public function findMany(string|null $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
        ->whereNull('deleted_at')
        ->get()
        ->map(function ($row) {
          return CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
        })
    )();
  }

  public function getById(string $id, string|null $tenantId)
  {
    return async(
      fn () => $this->getQueryBuilder()
        ->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
        ->whereNull('deleted_at')
        ->where('id', '=', $id)
        ->first()
    )()->then(function ($row) {
      if ($row === null) {
        throw new NotFoundException('Collaborator not found');
      }

      return CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
    });
  }
}
