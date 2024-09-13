<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class UnitTypeUseCases
{
  public UnitTypeByIdUseCase $unitTypeById;
  public UnitTypesFindManyUseCase $unitTypesFindMany;

  public UnitTypesCreateUseCase $unitTypesCreate;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitTypeById = new UnitTypeByIdUseCase($repositories, $userContext);
    $this->unitTypesFindMany = new UnitTypesFindManyUseCase($repositories, $userContext);
    $this->unitTypesCreate = new UnitTypesCreateUseCase($repositories, $userContext);
  }
}
