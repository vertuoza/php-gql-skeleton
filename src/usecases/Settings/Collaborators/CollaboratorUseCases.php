<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorByIdUseCase;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorsFindManyUseCase;

class CollaboratorUseCases
{
  public CollaboratorByIdUseCase $collaboratorById;
  public CollaboratorsFindManyUseCase $collaboratorsFindMany;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorById = new CollaboratorByIdUseCase($repositories, $userContext);
    $this->collaboratorsFindMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
  }
}
