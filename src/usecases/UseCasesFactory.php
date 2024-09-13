<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\Collaborators\CollaboratorUseCases;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;

  public CollaboratorUseCases $collaborator;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaborator = new CollaboratorUseCases($userContext, $repositories);
  }
}
