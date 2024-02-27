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

  private UserRequestContext $userContext;

  private RepositoriesFactory $repositories;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaboratorType = new CollaboratorByIdUseCase($repositories, $userContext);
    $this->collaboratorManyType = new CollaboratorTypesFindManyUseCase($repositories, $userContext);
    $this->userContext = $userContext;
    $this->repositories = $repositories;
  }

  public function get(string $className)
  {
      // Namespace should be complete in using ReflectionClass
      // if we have only one UsecasesFactory, we should not put the sub-folder in the namespace
      // or just put all usecases in one folder
      $class = new \ReflectionClass("Vertuoza\\Usecases\\Settings\\UnitTypes\\".ucfirst($className).'UseCase');
      return $class->newInstanceArgs([$this->repositories, $this->userContext]);
  }
}
