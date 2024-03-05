<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;
use function React\Async\async;

class CollaboratorRepository
{

	public function __construct(
		private QueryBuilder            $db,
		private PromiseAdapterInterface $dataLoaderPromiseAdapter
	)
	{
	}

	protected function getQueryBuilder()
	{
		return $this->db->getConnection()->table(CollaboratorModel::getTableName());
	}

	/** @return Promise<CollaboratorEntity[]> */
	public function findMany(string $tenantId)
	{
		return async(
			fn() => $this->getQueryBuilder()
				->whereNull('deleted_at')
				->where(function ($query) use ($tenantId) {
					$query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
						->orWhereNull(CollaboratorModel::getTenantColumnName());
				})
				->get()
				->map(fn($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
		)();
	}

	/** @return Promise<CollaboratorEntity> */
	public function getById(string|int $id, string $tenantId): Promise
	{
		return async(
			fn() => $this->getQueryBuilder()
				->whereNull('deleted_at')
				->where(function ($query) use ($tenantId) {
					$query->where(CollaboratorModel::getTenantColumnName(), '=', $tenantId)
						->orWhereNull(CollaboratorModel::getTenantColumnName());
				})
				->where('id', '=', $id)
				->limit(1)
				->get()
				->map(fn($row) => CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row)))
				->first()
		)();
	}
}
