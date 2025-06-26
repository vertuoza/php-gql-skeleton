<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Illuminate\Contracts\Console\Application;
use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Libs\Logger\ApplicationLogger;
use Vertuoza\Libs\Logger\LogContext;

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
   * @return Promise<CollaboratorEntity>
   */
  public function handle(string $id): Promise
  {
    $tenantId = $this->userContext->getTenantId();

    return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId())
      ->then(function ($collaborator) use ($id, $tenantId) {
        if (!$collaborator) {
          ApplicationLogger::getInstance()->warning("Collaborator not found", new LogContext($tenantId, $id));
          throw new NotFoundException("Collaborator with ID '$id' not found.");
        }

        ApplicationLogger::getInstance()->info("Collaborator retrieved successfully", new LogContext($tenantId, $id));
        return $collaborator;
      });
  }
}
