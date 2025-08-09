<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorsFindManyUseCase
{
  public function __construct(
    private CollaboratorRepository $collaboratorRepository,
    private UserRequestContext $userContext,
  ) {
  }

  /**
   * @return Promise<CollaboratorEntity>
   */
  public function handle()
  {
    return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
  }
}
