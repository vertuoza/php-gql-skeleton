<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Illuminate\Database\Query\Builder;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
    protected array $getByIdsDL;
    private QueryBuilder $db;
    protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

    public function __construct(
        QueryBuilder $database,
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
                ->where(function ($query) use ($tenantId) {
                    $query->where([CollaboratorModel::getTenantColumnName() => $tenantId])
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

    protected function getDataloader(string $tenantId): DataLoader
    {
        if (!isset($this->getByIdsDL[$tenantId])) {
            $dl = new DataLoader(function (array $ids) use ($tenantId) {
                return $this->fetchByIds($tenantId, $ids);
            }, $this->dataLoaderPromiseAdapter);
            $this->getByIdsDL[$tenantId] = $dl;
        }

        return $this->getByIdsDL[$tenantId];
    }

    protected function getQueryBuilder(): Builder
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
                ->whereNull('deleted_at')
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
}