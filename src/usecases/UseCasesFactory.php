<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorsUseCases;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;


class UseCasesFactory
{
  public UnitTypeUseCases $unitType;
  public CollaboratorsUseCases $collaborator;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaborator = new CollaboratorsUseCases($userContext, $repositories);
  }
}
