<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes;

use Illuminate\Database\Query\Builder;
use Overblog\DataLoader\DataLoader;
use Vertuoza\Repositories\AbstractRepository;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorModel;
use function React\Async\async;

class CollaboratorRepository extends AbstractRepository
{
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

            // Map the IDs to the corresponding entities, preserving the order of IDs.
            return collect($ids)
                ->map(fn ($id) => $entities->get($id))
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


    protected function getQueryBuilder(): Builder
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
    }

    public function countCollaboratorTypeWithName(string $name, string $tenantId, string|int|null $excludeId = null)
    {
        return async(
            fn () => $this->getQueryBuilder()
                ->where('name', $name)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($excludeId) {
                    if (isset($excludeId))
                        $query->where('id', '!=', $excludeId);
                })
                ->where(function ($query) use ($tenantId) {
                    $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(CollaboratorModel::getTenantColumnName());
                })
        )();
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

    public function create(CollaboratorTypeMutationData $data, string $tenantId): int|string
    {
        $newId = $this->getQueryBuilder()->insertGetId(
            CollaboratorMapper::serializeCreate($data, $tenantId)
        );
        return $newId;
    }

    public function update(string $id, CollaboratorTypeMutationData $data)
    {
        $this->getQueryBuilder()
            ->where(CollaboratorModel::getPkColumnName(), $id)
            ->update(CollaboratorMapper::serializeUpdate($data));

        $this->clearCache($id);
    }
}