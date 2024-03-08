<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeRepository;
use Vertuoza\Entities\Settings\CollaboratorTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorTypesFindManyUseCase
{
  private UserRequestContext $userContext;
  private CollaboratorTypeRepository $collaboratorTypeRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext,
  ) {
    $this->collaboratorTypeRepository = $repositories->collaboratorType;
    $this->userContext = $userContext;
  }

  /**
   * @param string $id id of the collaborator type to retrieve
   * @return Promise<CollaboratorTypeEntity>
   */
  public function handle()
  {
    return $this->collaboratorTypeRepository->findMany($this->userContext->getTenantId());
  }
}
