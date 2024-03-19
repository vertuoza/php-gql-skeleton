<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
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
     * @return Promise<UnitTypeEntity>|null
     */
    public function handle(string $name): ?Promise
    {
        if(empty($name)){
            throw new \Exception("Unit type is empty");
        }else if($this->unitTypeRepository->unitNameExists($name, $this->userContext->getTenantId())){
            throw new \Exception("Unit type already exists");
        }else {
            $data = new UnitTypeMutationData();
            $data->name = $name;

            $id = $this->unitTypeRepository->createGetUuid($data, $this->userContext->getTenantId());
            return $this->unitTypeRepository->getById($id, $this->userContext->getTenantId());
        }
        return null;
    }
}