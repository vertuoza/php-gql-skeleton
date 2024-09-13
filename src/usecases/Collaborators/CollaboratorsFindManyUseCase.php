<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorsFindManyUseCase
{
    private UserRequestContext $userContext;
    private CollaboratorRepository $collaboratorRepository;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
    ) {
        $this->collaboratorRepository = $repositories->collaborator;
        $this->userContext = $userContext;
    }

    /**
     * @return Promise<CollaboratorEntity[]>
     */
    public function handle(): Promise
    {
        return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
    }
}