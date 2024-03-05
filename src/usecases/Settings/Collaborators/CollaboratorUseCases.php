<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
  public CollaboratorsFindManyUseCase $collaboratorsFindMany;
  public CollaboratorByIdUseCases $collaboratorById;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorsFindMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
    $this->collaboratorById = new CollaboratorByIdUseCases($repositories, $userContext);
  }
}
