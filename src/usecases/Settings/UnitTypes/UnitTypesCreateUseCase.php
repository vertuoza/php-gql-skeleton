<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\RepositoriesFactory;
use Exception;

class UnitTypesCreateUseCase
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
     * @param UnitTypeMutationData $data data for creating a new unit type
     * @return Promise<UnitTypeEntity> created unit type
     * @throws Exception
     */
    public function handle(UnitTypeMutationData $data): Promise
    {
        /**
         * PDO::lastInsertId() not works because of using UUID() instead of AUTO_INCREMENT in seed/init.sql
         * One of the solutions is to generate UUID before creating a new record
         */
        $newId = $this->unitTypeRepository->generateId();
        $this->unitTypeRepository->create($data, $this->userContext->getTenantId(), $newId);
        return $this->unitTypeRepository->getById($newId, $this->userContext->getTenantId());
    }
}