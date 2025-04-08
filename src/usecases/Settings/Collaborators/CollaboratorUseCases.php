<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
  public CollaboratorUseCase $collaboratorById;
  public CollaboratorManyUseCase $collaboratorFindMany;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorById = new CollaboratorUseCase($repositories, $userContext);
    $this->collaboratorFindMany = new CollaboratorManyUseCase($repositories, $userContext);
  }
}
