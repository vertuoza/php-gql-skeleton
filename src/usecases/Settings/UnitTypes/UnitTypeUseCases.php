<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeUseCases
{
  public UnitTypeByIdUseCase $unitTypeById;
  public UnitTypesFindManyUseCase $unitTypesFindMany;
  public CreateUnitTypeUseCase $createUnitType;

  public function __construct(UserRequestContext $userContext, UnitTypeRepository $repository)
  {
    $this->unitTypeById = new UnitTypeByIdUseCase($repository, $userContext);
    $this->unitTypesFindMany = new UnitTypesFindManyUseCase($repository, $userContext);
    $this->createUnitType = new CreateUnitTypeUseCase($repository, $userContext);
  }
}
