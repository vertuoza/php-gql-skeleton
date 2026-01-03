<?php

namespace Vertuoza\Usecases;

use Vertuoza\Usecases\Collaborator\CollaboratorUseCases;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;
  public CollaboratorUseCases $collaborator_use_cases;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaborator_use_cases = new CollaboratorUseCases($userContext, $repositories);
  }
}
