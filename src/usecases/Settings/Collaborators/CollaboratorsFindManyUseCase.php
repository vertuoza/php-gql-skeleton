<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorsFindManyUseCase
{
  private UserRequestContext $userContext;
  private CollaboratorRepository $collaboratorRepository;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->collaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  public function handle()
  {
    return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
  }
}
