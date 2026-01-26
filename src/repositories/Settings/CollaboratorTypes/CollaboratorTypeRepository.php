<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeMapper;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeModel;

use function React\Async\async;

class CollaboratorTypeRepository
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

    protected function getQueryBuilder()
    {
        return $this->db->getConnection()->table(CollaboratorTypeModel::getTableName());
    }

    public function allCollaborators(string $tenantId)
    {
        return async(
            fn() => $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->where(CollaboratorTypeModel::getTenantColumnName(), '=', $tenantId)
                ->get()
                ->map(function ($row) {
                    return CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
                })
        )();
    }

    private function fetchByIds(string $tenantId, array $ids)
    {
        return async(function () use ($tenantId, $ids) {
            $query = $this->getQueryBuilder()
                ->where(function ($query) use ($tenantId) {
                    $query->where([CollaboratorTypeModel::getTenantColumnName() => $tenantId])
                        ->orWhere(CollaboratorTypeModel::getTenantColumnName(), null);
                });
            $query->whereNull('deleted_at');
            $query->whereIn(CollaboratorTypeModel::getPkColumnName(), $ids);

            $entities = $query->get()->mapWithKeys(function ($row) {
                $entity = CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
                return [$entity->id => $entity];
            });

            return collect($ids)
                ->map(fn($id) => $entities->get($id))
                ->toArray();
        })();
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

    public function getById(string $id, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->load($id);
    }
}
