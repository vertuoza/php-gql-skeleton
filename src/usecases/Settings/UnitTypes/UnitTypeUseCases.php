<?php

declare(strict_types=1);

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

/** Set of resolvers for unit type-related queries & mutations */
class UnitTypeUseCases
{
    public readonly UnitTypeByIdUseCase $unitTypeById;

    public readonly UnitTypesFindManyUseCase $unitTypesFindMany;

    public readonly UnitTypeCreateUseCase $unitTypeCreate;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->unitTypeById = new UnitTypeByIdUseCase($repositories, $userContext);
        $this->unitTypesFindMany = new UnitTypesFindManyUseCase($repositories, $userContext);
        $this->unitTypeCreate = new UnitTypeCreateUseCase($repositories, $userContext);
    }
}
