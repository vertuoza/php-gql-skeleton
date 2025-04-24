<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorUseCases;

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
