<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\NotAuthorizedException;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
{
	private UnitTypeRepository $repository;

	public function __construct(
		RepositoriesFactory $repositories,
		private UserRequestContext $userContext
	) {
		$this->repository = $repositories->unitType;
	}

	/**
	 * @param UnitTypeMutationData $mutationData
	 * @return Promise<UnitTypeEntity>
	 * @throws BadUserInputException
	 * @throws NotAuthorizedException
	 */
	public function handle(UnitTypeMutationData $mutationData): Promise
	{
		if (!$tenantId = $this->userContext->getTenantId()) {
			throw new NotAuthorizedException();
		}

		(new StringValidator('name', $mutationData->name))
			->notEmpty(true)
			->maxLength(255, true)
			->throwFirstError();

		$id = $this->repository->create($mutationData, $tenantId);

		return $this->repository->getById($id, $tenantId);
	}
}