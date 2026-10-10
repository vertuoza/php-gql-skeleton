<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

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

  public function handle(string $id): PromiseInterface
  {
    return $this->collaboratorRepository
      ->getById($id, $this->userContext->getTenantId())
      ->then(function ($collaborator) {
        if ($collaborator === null) {
          throw new NotFoundException(
            "The collaborator requested is not available"
          );
        }

        return $collaborator;
      });
  }
}
