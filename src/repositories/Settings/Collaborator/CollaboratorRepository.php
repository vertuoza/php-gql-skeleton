<?php

namespace Vertuoza\Repositories\Settings\Collaborator;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborator\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborator\Models\CollaboratorModel;
use Vertuoza\Repositories\Settings\Collaborator\Models\UnitTypeMapper;
use Vertuoza\Repositories\Settings\Collaborator\Models\UnitTypeModel;
use Vertuoza\Repositories\Settings\Collaborator\UnitTypeMutationData;

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

    protected function getQueryBuilder()
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
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
                ->where(function ($query) use ($tenantId) {
                    $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(CollaboratorModel::getTenantColumnName());
                })
                ->get()
                ->map(fn ($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
        )();
    }
}
