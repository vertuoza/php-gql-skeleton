<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;

class UnitTypesFindManyUseCase
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
   * @return Promise<UnitTypeEntity[]>
   */
  public function handle(): PromiseInterface
  {
    return $this->unitTypeRepository->findMany($this->userContext->getTenantId());
  }
}
