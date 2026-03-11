<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

use function React\Async\async;
use function React\Async\await;

class UnitTypeCreateUseCase
{
  private UserRequestContext $userContext;
  private UnitTypeRepository $unitTypeRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext,
  ) {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }

  /**
   * @param UnitTypeMutationData $data
   * @return UnitTypeEntity
   */
  public function handle(UnitTypeMutationData $data)
  {
    return async(function () use ($data) {
      $id = $this->unitTypeRepository->create($data, $this->userContext->getTenantId());
      return await($this->unitTypeRepository->getById((string) $id, $this->userContext->getTenantId()));
    })();
  }
}