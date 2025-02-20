<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitType;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeCreateInput;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
{
	private UserRequestContext $userContext;
	private UnitTypeRepository $unitTypeRepository;

	public function __construct(
		RepositoriesFactory $repositories,
		UserRequestContext $userContext,
	) {
		$this->unitTypeRepository = $repositories->unitType;
		$this->userContext = $userContext;
	}

	public function handle(array $input): Promise
	{
		$newId = $this->unitTypeRepository->create(new UnitTypeMutationData($input['name']), $this->userContext->getTenantId());

		return $this->unitTypeRepository->getById($newId, $this->userContext->getTenantId());
	}
}