<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorUseCases
{
  public CollaboratorByIdUseCase $collaboratorById;
  public CollaboratorsFindManyUseCase $collaboratorsFindMany;


  public function __construct(UserRequestContext $userContext, CollaboratorRepository $repository)
  {
    $this->collaboratorById = new CollaboratorByIdUseCase($repository, $userContext);
    $this->collaboratorsFindMany = new CollaboratorsFindManyUseCase($repository, $userContext);
  }
}
