<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorByIdUseCase
{
  public function __construct(
    private CollaboratorRepository $collaboratorRepository,
    private UserRequestContext $userContext,
  ) {
  }

  /**
   * @param string $id id of the collaborator to retrieve
   * @return Promise<CollaboratorEntity>
   */
  public function handle(string $id): Promise
  {
    return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId());
  }
}
