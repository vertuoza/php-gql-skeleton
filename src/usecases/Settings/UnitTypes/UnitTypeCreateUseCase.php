<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
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
   * @param string $data
   *
   * @return Promise<UnitTypeEntity>
   */
  public function handle(string $name): Promise
  {
    $data = new UnitTypeMutationData();
    $data->name = $name;
    
    $id = $this->unitTypeRepository->create($data, $this->userContext->getTenantId());
    // manage error here.
    return $this->unitTypeRepository->getById($id, $this->userContext->getTenantId());
  }
}
