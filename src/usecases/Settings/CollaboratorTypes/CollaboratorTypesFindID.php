<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorTypesFindId
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

    public function findCollaborator(string $id)
    {
        return $this->repositories->collaborators->getById($id, $this->userContext->getTenantId());
    }
}
