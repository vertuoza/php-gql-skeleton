<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateInputUseCase
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
     * @param string $name name of the unit type to create
     * @return Promise<UnitTypeEntity>
     */
    public function handle(string $name): Promise
    {
        $data = new UnitTypeMutationData();
        $data->name = $name;

        $id = $this->unitTypeRepository->create($data, $this->userContext->getTenantId());

        return $this->unitTypeRepository->getById($id, $this->userContext->getTenantId());
    }
}