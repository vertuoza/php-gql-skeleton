<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
    public CollaboratorByIdUseCase $collaboratorById;
    public CollaboratorsFindManyUseCase $collaboratorFindMany;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
      $this->collaboratorById = new CollaboratorByIdUseCase($repositories, $userContext);
      $this->collaboratorFindMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
  }
}
