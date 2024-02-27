<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
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
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle(string $name): Promise
    {
        $mutationData = new UnitTypeMutationData();
        $mutationData->name = $name;
        $newId = $this->unitTypeRepository->create($mutationData, $this->userContext->getTenantId());
        //var_dump($newId);
        //var_dump($this->unitTypeRepository->getById($newId, $this->userContext->getTenantId()));
        //die();
        return $this->unitTypeRepository->getById($newId, $this->userContext->getTenantId());
    }
}