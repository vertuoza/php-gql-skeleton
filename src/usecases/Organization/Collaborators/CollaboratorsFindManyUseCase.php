<?php

namespace Vertuoza\Usecases\Organization\Collaborators;

use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Organization\CollaboratorEntity;
use Vertuoza\Repositories\Organization\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorsFindManyUseCase
{
  private UserRequestContext $userContext;
  private CollaboratorRepository $collaboratorRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext,
  ) {
    $this->collaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  /**
   * @return Promise<array<int, CollaboratorEntity>>
   */
  public function handle(): PromiseInterface
  {
    return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
  }
}
