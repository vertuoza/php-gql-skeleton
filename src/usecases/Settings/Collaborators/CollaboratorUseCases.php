<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
  public CollaboratorsFindManyUseCase $collaboratorsFindMany;


  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorsFindMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
  }
}
