<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\FindByIdUseCase;
use Vertuoza\Usecases\FindManyUseCase;
use Vertuoza\Usecases\RepositoryType;

class CollaboratorUseCases
{
    public FindByIdUseCase $findById;
    public FindManyUseCase $findMany;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->findById = new FindByIdUseCase($repositories, $userContext, RepositoryType::Collaborator);
        $this->findMany = new FindManyUseCase($repositories, $userContext, RepositoryType::Collaborator);
    }
}