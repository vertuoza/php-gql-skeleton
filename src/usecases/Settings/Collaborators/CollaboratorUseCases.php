<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
    public CollaboratorByIdUseCase $collaboratorById;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->collaboratorById = new CollaboratorByIdUseCase($repositories, $userContext);
    }
}
