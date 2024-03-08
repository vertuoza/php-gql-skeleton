<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorsByIdUseCase
{
  private CollaboratorRepository $CollaboratorRepository;
  private UserRequestContext $userContext;
  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext
  ) {
    $this->CollaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  /**
   * @param string $id id of the unit type to retrieve
   * @return Promise<CollaboratorEntity>
   */
  public function handle(string $id): Promise
  {
    return $this->CollaboratorRepository->getById($id, $this->userContext->getTenantId());
  }
}
