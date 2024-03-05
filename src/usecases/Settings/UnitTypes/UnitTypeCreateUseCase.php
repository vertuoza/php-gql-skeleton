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
   * @param array $data
   * @return Promise<UnitTypeEntity>
   * @throws \Exception
   */
  public function handle(array $data): Promise {
    if(empty($data['name'])) {
      throw new \Exception('Name is required');
    }
    $unitTypeData = new UnitTypeMutationData();
    $unitTypeData->name = $data['name'];
    return $this->unitTypeRepository->getById(
      $this->unitTypeRepository->create($unitTypeData, $this->userContext->getTenantId()),
      $this->userContext->getTenantId()
    );
  }
}