<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypesFindManyUseCase
{

  public function __construct(
    private UnitTypeRepository $unitTypeRepository,
    private UserRequestContext $userContext,
  )
  {
  }

  /**
   * @param string $id id of the unit type to retrieve
   * @return Promise<UnitTypeEntity>
   */
  public function handle()
  {
    return $this->unitTypeRepository->findMany($this->userContext->getTenantId());
  }
}
