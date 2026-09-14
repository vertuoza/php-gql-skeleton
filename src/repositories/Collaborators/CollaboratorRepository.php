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
    protected array $getByIdsDL;

    private QueryBuilder $db;

    protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

    public function __construct(
        private QueryBuilder $database,
        PromiseAdapterInterface $dataLoaderPromiseAdapter
    ) {
        $this->db = $database;
        $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
        $this->getByIdsDL = [];
    }

    private function fetchByIds(string $tenantId, array $ids)
    {
        return async(function () use ($tenantId, $ids) {
            $query = $this->getQueryBuilder()
                ->where(CollaboratorModel::getTenantColumnName(), $tenantId)
                ->whereNull('deleted_at')
                ->whereIn(CollaboratorModel::getPkColumnName(), $ids);

            $entities = $query->get()->mapWithKeys(function ($row) {
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
            $this->getByIdsDL[$tenantId] = new DataLoader(function (array $ids) use ($tenantId) {
                return $this->fetchByIds($tenantId, $ids);
            }, $this->dataLoaderPromiseAdapter);
        }

        return $this->getByIdsDL[$tenantId];
    }


    protected function getQueryBuilder()
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
    }

    public function getByIds(array $ids, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->loadMany($ids);
    }

    public function getById(string $id, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->load($id);
    }

    public function findMany(string $tenantId)
    {
        return async(
            fn () => $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->where(CollaboratorModel::getTenantColumnName(), $tenantId)
                ->get()
                ->map(function ($row) {
                    return CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
                })
        )();
    }
}