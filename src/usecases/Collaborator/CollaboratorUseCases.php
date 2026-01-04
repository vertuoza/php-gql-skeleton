<?php

namespace Vertuoza\Usecases\Collaborator;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
    public CollaboratorsFindManyUseCase $collaborators_find_many_use_case;

    public CollaboratorByIdUseCase $collaborator_by_id_use_case;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->collaborators_find_many_use_case = new CollaboratorsFindManyUseCase($repositories, $userContext);
        $this->collaborator_by_id_use_case = new CollaboratorByIdUseCase($repositories, $userContext);
    }
}
