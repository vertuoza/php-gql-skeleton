<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeCreateUseCase
{
  private UnitTypeRepository $unitTypeRepository;
  private UserRequestContext $userContext;
  
  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }

  public function handle(UnitTypeMutationData $data): Promise
  {
    $id = $this->unitTypeRepository->create($data, $this->userContext->getTenantId());
    return $this->unitTypeRepository->getById($id, $this->userContext->getTenantId());
  }
}
