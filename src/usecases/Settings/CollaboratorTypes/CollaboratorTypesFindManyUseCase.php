<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorTypesFindManyUseCase
{
    private UserRequestContext $userContext;
    private RepositoriesFactory $repositories;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
    ) {
        $this->repositories = $repositories;
        $this->userContext = $userContext;
    }

    public function handle()
    {
        return $this->repositories->collaborators->allCollaborators($this->userContext->getTenantId());
    }
}
