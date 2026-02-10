<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use InvalidArgumentException;
use React\Promise\Promise;
use RuntimeException;
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
    )
    {
        $this->unitTypeRepository = $repositories->unitType;
        $this->userContext = $userContext;
    }

     public function handle(string $name): Promise
    {
        $tenantId = $this->userContext->getTenantId();
        return $this->unitTypeRepository->countUnitTypeWithLabel($name, $tenantId)
        ->then(function ($result) use ($name, $tenantId) {
                $count = (int) $result->count();
                if($count > 0){
                    throw new RuntimeException("Unit type : {$name} for tenant : {$tenantId} already exists");
                }
                $mutationData = new UnitTypeMutationData();
                $mutationData->name = $name;

                $this->unitTypeRepository->create($mutationData, $tenantId);
                return $this->unitTypeRepository->findLastCreated($name, $tenantId);
        });
    }
}