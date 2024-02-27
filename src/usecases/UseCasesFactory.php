<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\CollaboratorTypes\CollaboratorByIdUseCase;
use Vertuoza\Usecases\Settings\CollaboratorTypes\CollaboratorTypesFindManyUseCase;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;
  public CollaboratorByIdUseCase $collaboratorType;
  public CollaboratorTypesFindManyUseCase $collaboratorManyType;
  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaboratorType = new CollaboratorByIdUseCase($repositories, $userContext);
    $this->collaboratorManyType = new CollaboratorTypesFindManyUseCase($repositories, $userContext);
  }
}
