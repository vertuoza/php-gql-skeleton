<?php

namespace Vertuoza\Repositories\Organization\Collaborators;

use Illuminate\Database\Query\Builder;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Vertuoza\Entities\Organization\CollaboratorEntity;
use Vertuoza\Repositories\Database\QueryBuilder;

use Vertuoza\Repositories\Organization\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Organization\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
    protected array $getbyIdsDL;
    private QueryBuilder $db;
    protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

    public function __construct(
        private readonly QueryBuilder $database,
        PromiseAdapterInterface $dataLoaderPromiseAdapter
    ) {
        $this->db = $database;
        $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
        $this->getbyIdsDL = [];
    }

    private function fetchByIds(string $tenantId, array $ids): PromiseInterface
    {
        return async(function () use ($tenantId, $ids) {
            $query = $this->getQueryBuilder()
                ->where([CollaboratorModel::getTenantColumnName() => $tenantId])
                ->whereNull('deleted_at')
                ->whereIn(CollaboratorModel::getPkColumnName(), $ids);

            $entitiesById = $query->get()
                ->map(fn($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
                ->keyBy('id');

            // Map the IDs to the corresponding entities, preserving the order of IDs.
            return collect($ids)
                ->map(fn($id) => $entitiesById->get($id))
                ->toArray();
        })();
    }

    protected function getDataLoader(string $tenantId): DataLoader
    {
        if (!isset($this->getbyIdsDL[$tenantId])) {
            $dl = new DataLoader(function (array $ids) use ($tenantId) {
                return $this->fetchByIds($tenantId, $ids);
            }, $this->dataLoaderPromiseAdapter);
            $this->getbyIdsDL[$tenantId] = $dl;
        }

        return $this->getbyIdsDL[$tenantId];
    }

    protected function getQueryBuilder(): Builder
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
    }


    public function getByIds(array $ids, string $tenantId): PromiseInterface
    {
        return $this->getDataLoader($tenantId)->loadMany($ids);
    }

    public function getById(string $id, string $tenantId): PromiseInterface
    {
        return $this->getDataLoader($tenantId)->load($id);
    }

    /**
     * @param string $tenantId
     * @return PromiseInterface<array<int, CollaboratorEntity>>
     */
    public function findMany(string $tenantId): PromiseInterface
    {
        return async(
            fn() => $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                ->orderBy('name')
                ->orderBy('first_name')
                ->get()
                ->map(function ($row) {
                    return CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
                })
                ->values()
                ->all()
        )();
    }
}
