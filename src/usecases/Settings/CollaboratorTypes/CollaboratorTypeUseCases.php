<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorTypeUseCases
{
  public CollaboratorTypeByIdUseCase $collaboratorTypeById;
  public CollaboratorTypesFindManyUseCase $collaboratorTypesFindMany;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorTypeById = new CollaboratorTypeByIdUseCase($repositories, $userContext);
    $this->collaboratorTypesFindMany = new CollaboratorTypesFindManyUseCase($repositories, $userContext);
  }
}

