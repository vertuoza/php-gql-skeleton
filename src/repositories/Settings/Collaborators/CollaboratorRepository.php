<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

class CollaboratorRepository
{
    private array $getByIdsDL = [];

    public function __construct(
        private QueryBuilder $db,
        private PromiseAdapterInterface $dataLoaderPromiseAdapter
    ) {
    }

    /**
     * Fetch collaborators by their IDs for a given tenant
     * @param array $ids list of IDs for the collaborators
     * @param string|null $tenantId ID of the tenant
     * @return Promise<array<string, CollaboratorEntity>> array of ID => Collaborator
     */
    private function fetchByIds(array $ids, ?string $tenantId = null): Promise
    {
        return async(function () use ($tenantId, $ids) {
            $query = $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->whereIn(CollaboratorModel::getPkColumnName(), $ids)

                ->when($tenantId !== null, fn($query) =>
                    $query->where(function ($query) use ($tenantId) {
                        // Collaborators for the given tenant or global ones
                        $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                            ->orWhereNull(CollaboratorModel::getTenantColumnName());
                    })
                )
                ->when($tenantId === null, fn($query) =>
                    // Collaborators without tenant
                    $query->whereNull(CollaboratorModel::getTenantColumnName())
                )

                ->where(function ($query) use ($tenantId) {
                    // Ensures only collaborators for a tenant or global ones (tenant IS NULL)
                    // are returned without affecting other filters.
                    $query->where(CollaboratorModel::getTenantColumnName(), $tenantId)
                        ->orWhereNull(CollaboratorModel::getTenantColumnName());
                });

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

    /**
     * Returns a DataLoader instance for the given tenant
     * creates it if it doesn't exist, to cache entities by IDs
     * @param string|null $tenantId
     * @return DataLoader
     */
    private function getDataLoader(?string $tenantId = null): DataLoader
    {
        // global is as key used when $tenantId is null
        $cacheKey = $tenantId ?? '__global__';

        if (!isset($this->getByIdsDL[$cacheKey])) {
            $dl = new DataLoader(function (array $ids) use ($tenantId) {
                return $this->fetchByIds($ids, $tenantId);
            }, $this->dataLoaderPromiseAdapter);
            $this->getByIdsDL[$cacheKey] = $dl;
        }

        return $this->getByIdsDL[$cacheKey];
    }


    private function getQueryBuilder()
    {
        return $this->db->getConnection()->table(CollaboratorModel::getTableName());
    }

    /**
     * Get one Collaborator by ID
     *
     * @param string $id ID of the Collaborator
     * @param string|null $tenantId ID of the tenant
     * @return Promise<CollaboratorEntity>
     */
    public function getById(string $id, ?string $tenantId = null): Promise
    {
        return $this->getDataLoader($tenantId)->load($id);
    }

    /**
     * Get all Collaborators from a tenant
     *
     * If $tenantId is null, returns all Collaborators without a tenant
     * If $tenantId is not null, returns all Collaborators for that tenant and global ones
     *
     * @param string|null $tenantId ID of the tenant
     * @return Promise<array<CollaboratorEntity>>
     */
    public function findMany(?string $tenantId = null): Promise
    {
        return async(fn () =>
            $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->when($tenantId !== null, fn($query) =>
                    $query->where(function ($query) use ($tenantId) {
                        // Collaborators for the given tenant or global ones
                        $query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
                            ->orWhereNull(CollaboratorModel::getTenantColumnName());
                    })
                )
                ->when($tenantId === null, fn($query) =>
                    // Collaborators without tenant
                    $query->whereNull(CollaboratorModel::getTenantColumnName())
                )
                ->get()
                ->map(fn($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
        )();
    }
}
