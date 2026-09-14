<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Libs\Exceptions\NotFoundException;

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
        return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId())
            ->then(function (?CollaboratorEntity $collaborator) use ($id) {
                if ($collaborator === null) {
                    throw new NotFoundException("Collaborator with ID {$id} not found");
                }
                return $collaborator;
            });
    }
}