<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\FindByIdUseCase;
use Vertuoza\Usecases\FindManyUseCase;
use Vertuoza\Usecases\RepositoryType;

class UnitTypeUseCases
{
  public FindByIdUseCase $findById;
  public FindManyUseCase $findMany;
  public CreateUnitTypeUseCase $initializer;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->findById = new FindByIdUseCase($repositories, $userContext, RepositoryType::Unit);
    $this->findMany = new FindManyUseCase($repositories, $userContext, RepositoryType::Unit);
    $this->initializer = new CreateUnitTypeUseCase($repositories, $userContext, RepositoryType::Unit);
  }
}
