<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

/**
 * Use case for retrieving a single collaborator by its ID.
 * Scopes the query to the current user's tenant.
 */
class CollaboratorByIdUseCase
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
   * Retrieves a collaborator by its ID for the current user's tenant.
   *
   * @param string $id The ID of the collaborator to retrieve
   * @return \React\Promise\Promise<CollaboratorEntity>
   * @throws NotFoundException If the collaborator does not exist or belongs to another tenant
   */
  public function handle(string $id)
  {
    return $this->collaboratorRepository
      ->getById($id, $this->userContext->getTenantId())
      ->then(function (?CollaboratorEntity $collaborator) use ($id) {
        if ($collaborator === null) {
          throw new NotFoundException("Collaborator $id not found");
        }
        return $collaborator;
      });
  }
}