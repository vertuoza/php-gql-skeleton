<?php

namespace Vertuoza\Usecases\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;

class CollaboratorsFindManyUseCase
{
  private CollaboratorRepository $collaboratorRepository;
  private UserRequestContext $userContext;

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