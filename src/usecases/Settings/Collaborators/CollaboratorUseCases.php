<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

/**
 * Aggregates all use cases related to collaborators.
 * Acts as a single entry point for collaborator business logic.
 */
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