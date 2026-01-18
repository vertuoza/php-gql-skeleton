<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
    public function __construct(
        private UserRequestContext $userContext,
        private RepositoriesFactory $repositories
    ) {}

    public function findMany()
    {
        // On récupère le tenantId depuis le contexte utilisateur
        $tenantId = $this->userContext->getTenantId();
        return $this->repositories->collaborator->findMany($tenantId);
    }

    public function findById(string $id)
    {
        $tenantId = $this->userContext->getTenantId();
        return $this->repositories->collaborator->getById($id, $tenantId);
    }
}