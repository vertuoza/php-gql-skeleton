<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Illuminate\Contracts\Console\Application;
use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Libs\Logger\ApplicationLogger;
use Vertuoza\Libs\Logger\LogContext;
use Illuminate\Support\Collection;

class CollaboratorsFindManyUseCase
{
  private UserRequestContext $userContext;
  private CollaboratorRepository $collaboratorRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext
  ) {
    $this->collaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  /**
   * @param string $id id of the collaborator to retrieve
   * @return Promise<CollaboratorEntity[]>
   */
  public function handle()
  {
    $tenantId = $this->userContext->getTenantId();

    return $this->collaboratorRepository->findMany($tenantId)
      ->then(function (Collection $collaborators) use ($tenantId) {
        ApplicationLogger::getInstance()->info("Collaborators retrieved successfully", new LogContext($tenantId, null));
        
        return $collaborators;
      });
  }
}
