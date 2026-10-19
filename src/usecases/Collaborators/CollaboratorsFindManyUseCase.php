<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Collaborators\CollaboratorEntity;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorsFindManyUseCase
{
  private CollaboratorRepository $collaboratorRepository;
  private UserRequestContext $userContext;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext
  ) {
    $this->collaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  /**
   * @return PromiseInterface<iterable<CollaboratorEntity>> the collaborators of the tenant of the user
   */
  public function handle(): PromiseInterface
  {
    return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
  }
}
