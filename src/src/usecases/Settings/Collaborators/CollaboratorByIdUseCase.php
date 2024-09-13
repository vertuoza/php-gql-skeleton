<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
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

    /**
     * @param string $id id of the collaborator to retrieve
     * @return Promise<CollaboratorEntity>
     */
    public function handle(string $id): Promise
    {
        return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId());
    }
}