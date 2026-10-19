<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Collaborators\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorByIdUseCase
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
   * @param string $id id of the collaborator to retrieve
   * @return PromiseInterface<CollaboratorEntity>
   * @throws NotFoundException when the collaborator does not exist for the tenant of the user
   */
  public function handle(string $id): PromiseInterface
  {
    return $this->collaboratorRepository
      ->getById($id, $this->userContext->getTenantId())
      ->then(function (?CollaboratorEntity $collaborator) use ($id) {
        if ($collaborator === null) {
          throw new NotFoundException("Collaborator not found", "NOT_FOUND", null, ['id' => $id]);
        }
        return $collaborator;
      });
  }
}
