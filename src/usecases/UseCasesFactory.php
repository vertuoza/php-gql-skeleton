<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories->unitType);
  }
}
