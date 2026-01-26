<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorUseCases
{
    public CollaboratorTypesFindManyUseCase $findMany;
    public CollaboratorTypesFindId $findId;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->findMany = new CollaboratorTypesFindManyUseCase($repositories, $userContext);
        $this->findId = new CollaboratorTypesFindId($repositories, $userContext);
    }
}