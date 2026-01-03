<?php

namespace Vertuoza\Usecases\Collaborator;


use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use React\Promise\Promise;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Entities\CollaboratorEntity;


class CollaboratorByIdUseCase
{

    private UserRequestContext $userContext;
    private CollaboratorRepository $collaborator_repository;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
    ) {
        $this->collaborator_repository = $repositories->collaborator_repository;
        $this->userContext = $userContext;
    }


    /**
     * @param string $id id of the unit type to retrieve
     * @return Promise<CollaboratorEntity>
     */
    public function handle(string $id)
    {
        return $this->collaborator_repository->getById($id, $this->userContext->getTenantId());
    }
}
