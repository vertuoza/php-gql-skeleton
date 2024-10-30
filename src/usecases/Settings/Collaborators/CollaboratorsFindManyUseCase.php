<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

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
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle()
    {
        return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
    }
}