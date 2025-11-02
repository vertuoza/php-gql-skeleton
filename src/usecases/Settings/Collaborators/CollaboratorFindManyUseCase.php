<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorFindManyUseCase
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
     * Use case to retrieve all Collaborator available for the current user
     * @return Promise<array<CollaboratorEntity>>
     */
    public function handle(): Promise
    {
        return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
    }
}
