<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Entities\Settings\CollaboratorEntity;
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
   * @return Promise<CollaboratorEntity>
   */
  public function handle()
  {
    return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
  }
}
