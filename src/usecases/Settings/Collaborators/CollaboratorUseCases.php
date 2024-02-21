<?php

declare(strict_types=1);

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

/** Set of resolvers for collaborator-related queries */
class CollaboratorUseCases
{
    public readonly CollaboratorByIdUseCase $byId;

    public readonly CollaboratorsFindManyUseCase $findMany;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->byId = new CollaboratorByIdUseCase($repositories, $userContext);
        $this->findMany = new CollaboratorsFindManyUseCase($repositories, $userContext);
    }
}
