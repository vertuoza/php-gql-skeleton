<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;

class UnitTypeCreateUseCase
{
  private UserRequestContext $userContext;
  private UnitTypeRepository $unitTypeRepository;

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
  public function handle(array $data): Promise
  {
    if (empty($data['name'])) {
      throw new \Exception('Name is a required parameter');
    }

    $unitTypeData = new UnitTypeMutationData();
    $unitTypeData->name = $data['name'];

    $unitTypeEntity = $this->unitTypeRepository->getById(
      $this->unitTypeRepository->create($unitTypeData, $this->userContext->getTenantId()),
      $this->userContext->getTenantId()
    );
    return $unitTypeEntity;
  }
}