<?php

declare(strict_types=1);

namespace Vertuoza\Repositories\Settings\Collaborators;

use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

use function React\Async\async;

/**
 * @phpstan-import-type RowObjectShape from CollaboratorMapper
 */
class CollaboratorRepository
{
    /**
     * In-memory cache for dataloaders for each tenant.
     * @var array<string, DataLoader>
     */
    private array $dataloaderCache = [];

    /** @param PromiseAdapterInterface<PromiseInterface<mixed>> $promiseAdapter */
    public function __construct(
        private readonly QueryBuilder $queryBuilder,
        /** @var PromiseAdapterInterface<PromiseInterface<mixed>> */
        private readonly PromiseAdapterInterface $promiseAdapter,
    ) {
    }

    /**
     * Get all collaborators for a tenant.
     * @return PromiseInterface<list<CollaboratorEntity>>
     */
    public function getByTenantId(string $tenantId): PromiseInterface
    {
        return async(function () use ($tenantId) {
            // Build a query returning the records that were not deleted and belong to the given tenant.
            $query = $this->getQueryBuilder()
                ->where([CollaboratorModel::getTenantColumnName() => $tenantId])
                ->whereNull('deleted_at');

            // Map the data to API entities
            return array_values(iterator_to_array($this->getQueryResults($query)));
        })();
    }

    /**
     * Get a single collaborator by its ID, if it exists and belongs to the given tenant.
     * @return PromiseInterface<CollaboratorEntity|null>
     */
    public function getByIdAndTenantId(string $id, string $tenantId): PromiseInterface
    {
        return $this->getDataloader($tenantId)->load($id);
    }

    /**
     * Fetch set of records by their IDs. The method is built for the DataLoader in order
     * to batch the requests and avoid N+1 queries.
     * @param string[] $ids
     * @return PromiseInterface<list<CollaboratorEntity|null>>
     */
    private function fetchByIds(string $tenantId, array $ids): PromiseInterface
    {
        return async(function () use ($tenantId, $ids) {
            // Build a query returning the records with the given IDs that were not deleted
            // and belong to the given tenant.
            $query = $this->getQueryBuilder()
                ->where([CollaboratorModel::getTenantColumnName() => $tenantId])
                ->whereNull('deleted_at')
                ->whereIn(CollaboratorModel::getPkColumnName(), $ids);

            // Map the data to API entities
            $buffer = iterator_to_array($this->getQueryResults($query));

            // Prepare the results in the same order as the input IDs
            $results = [];
            foreach ($ids as $id) {
                $results[] = $buffer[$id] ?? null;
            }
            return $results;
        })();
    }

    /**
     * Get dataloader built for a specific tenant ID. The dataloader will batch the requests if
     * multiple IDs are requested in a single request.
     *
     * Subsequent executions for the same tenant ID will return the same DataLoader instance.
     */
    private function getDataloader(string $tenantId): DataLoader
    {
        if (array_key_exists($tenantId, $this->dataloaderCache)) {
            return $this->dataloaderCache[$tenantId];
        }

        $this->dataloaderCache[$tenantId] = new DataLoader(
            function (array $ids) use ($tenantId) {
                return $this->fetchByIds($tenantId, $ids);
            },
            $this->promiseAdapter
        );

        return $this->dataloaderCache[$tenantId];
    }

    /**
     * Map a query to a list of entities.
     * @return iterable<string, CollaboratorEntity>
     * @throws Exception
     */
    private function getQueryResults(Builder $query): iterable
    {
        // Get results from the database
        /** @var Collection<array-key, RowObjectShape> $rows */
        $rows = $query->get();

        // Map the data to API entities
        foreach ($rows as $row) {
            yield $row->id => CollaboratorMapper::objectToEntity($row);
        }
    }


    /** Get query builder configure to query the collaborators table */
    private function getQueryBuilder(): Builder
    {
        return $this->queryBuilder->getConnection()->table(CollaboratorModel::getTableName());
    }
}
