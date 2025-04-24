<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeCreateUseCase;

class UnitTypeUseCases
{
  public UnitTypeByIdUseCase $unitTypeById;
  public UnitTypesFindManyUseCase $unitTypesFindMany;
  public UnitTypeCreateUseCase $unitTypeCreateUseCase;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitTypeById = new UnitTypeByIdUseCase($repositories, $userContext);
    $this->unitTypesFindMany = new UnitTypesFindManyUseCase($repositories, $userContext);
    $this->unitTypeCreateUseCase = new UnitTypeCreateUseCase($repositories, $userContext);
  }
}
