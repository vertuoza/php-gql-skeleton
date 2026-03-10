<?php
namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
  public CollaboratorGetAllUseCase $collaboratorFindMany;
  public CollaboratorGetByIdUseCase $collaboratorById;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->collaboratorFindMany = new CollaboratorGetAllUseCase($repositories);
    $this->collaboratorById = new CollaboratorGetByIdUseCase($repositories);
  }
}