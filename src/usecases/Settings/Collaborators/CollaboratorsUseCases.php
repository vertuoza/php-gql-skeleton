<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorsUseCases
{
  public CollaboratorsByIdUseCase $collaboratorsById;
  public CollaboratorsFindManyUseCase $collaboratorsFindMany;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorsById = new CollaboratorsByIdUseCase($repositories, $userContext);
    $this->collaboratorsFindMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
  }
}
