<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Usecases\Settings\CollaboratorTypes\CollaboratorTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;
  public CollaboratorTypeUseCases $collaboratorType;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaboratorType = new CollaboratorTypeUseCases($userContext, $repositories);
  }
}
