<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Libs\Exceptions\NotFoundException;

class CollaboratorByIdUseCase
{
  private CollaboratorRepository $collaboratorRepository;
  private UserRequestContext $userContext;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->collaboratorRepository = $repositories->collaborator;
    $this->userContext = $userContext;
  }

  public function handle(string $id): Promise
  {
    return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId())
      ->then(function ($entity) use ($id) {
        if ($entity === null) {
          throw new NotFoundException(
            "Collaborator with id \"{$id}\" not found.",
            "COLLABORATOR_NOT_FOUND",
            null,
            ['id' => $id]
          );
        }
        return $entity;
      });
  }
}