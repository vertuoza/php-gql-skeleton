<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Validation\InputStringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;


class CollaboratorByIdUseCase
{
    private CollaboratorRepository $collaboratorRepository;

    public function __construct(
        RepositoriesFactory $repositories,
        private UserRequestContext $userContext
    ) {
        $this->collaboratorRepository = $repositories->collaborator;
    }

    /**
     * Use case to retrieve a single Collaborator by its ID.
     *
     * @param string $id id of the collaborator to retrieve
     * @return Promise<CollaboratorEntity>
     * @throws BadUserInputException
     */
    public function handle(string $id): Promise
    {
        // Check if the ID is a valid UUID
        InputStringValidator::validateUuid($id);

        return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId());
    }
}
