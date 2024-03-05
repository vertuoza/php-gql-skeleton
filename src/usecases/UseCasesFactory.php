<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorUseCases;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;

class UseCasesFactory
{
  public CollaboratorUseCases $collaborator;
  public UnitTypeUseCases $unitType;
  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaborator = new CollaboratorUseCases($userContext, $repositories);
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
  }
}
