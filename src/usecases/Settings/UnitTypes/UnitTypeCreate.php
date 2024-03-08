<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreate
{
    private UnitTypeRepository $unitTypeRepository;
    private UserRequestContext $userContext;
    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->unitTypeRepository = $repositories->unitType;
        $this->userContext = $userContext;
    }

    /**
     * @param UnitTypeMutationData $unitTypeMutation name of the unit type to create
     * @return Promise<UnitTypeEntity>
     */
    public function handle(UnitTypeMutationData $unitTypeMutation): Promise
    {
        //return $this->unitTypeRepository->create($unitTypeMutation, $this->userContext->getTenantId())->then( fn(string $newId) => $this->unitTypeRepository->getById($newId, $this->userContext->getTenantId()));
        return $this->unitTypeRepository->getById(
            $this->unitTypeRepository->create($unitTypeMutation, $this->userContext->getTenantId()),
            $this->userContext->getTenantId());
    }
}
