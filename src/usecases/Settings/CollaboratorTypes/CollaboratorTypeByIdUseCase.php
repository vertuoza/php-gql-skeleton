<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeRepository;

class CollaboratorTypeByIdUseCase
{
  private CollaboratorTypeRepository $collaboratorTypeRepository;
  private UserRequestContext $userContext;
  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext
  ) {
    $this->collaboratorTypeRepository = $repositories->collaboratorType;
    $this->userContext = $userContext;
  }

  /**
   * @param string $id id of the collaborator type to retrieve
   * @return Promise<CollaboratorTypeEntity>
   */
  public function handle(string $id): Promise
  {
    return $this->collaboratorTypeRepository->getById($id, $this->userContext->getTenantId());
  }
}
