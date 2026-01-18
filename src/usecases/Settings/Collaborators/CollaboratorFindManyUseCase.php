<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorFindManyUseCase
{
    public function __construct(
        private RepositoriesFactory $repositoriesFactory
    ) {}

    public function execute(string $tenantId)
    {
        return $this->repositoriesFactory->collaborator->findMany($tenantId);
    }
}