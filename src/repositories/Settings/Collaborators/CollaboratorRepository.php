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
        private QueryBuilder $database,
        protected PromiseAdapterInterface $dataLoaderPromiseAdapter
    ) {
        $this->db = $database;
    }

    protected function getQueryBuilder()
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
    }

    // Récupérer tous les collaborateurs d'un tenant
    public function findMany(string $tenantId)
    {
        return async(
            fn () => $this->getQueryBuilder()
                ->where(function ($query) use ($tenantId) {
                    $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(CollaboratorModel::getTenantColumnName());
                })
                ->get()
                ->map(function ($row) {
                    return CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
                })
        )();
    }

    // Récupérer un collaborateur par son ID
    public function getById(string $id, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->load($id);
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

    private function fetchByIds(string $tenantId, array $ids)
    {
        return async(function () use ($tenantId, $ids) {
            $entities = $this->getQueryBuilder()
                ->whereIn(CollaboratorModel::getPkColumnName(), $ids)
                ->get()
                ->mapWithKeys(function ($row) {
                    $entity = CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
                    return [$entity->id => $entity];
                });

            return collect($ids)->map(fn ($id) => $entities->get($id))->toArray();
        })();
    }
}