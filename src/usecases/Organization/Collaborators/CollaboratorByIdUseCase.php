<?php

namespace Vertuoza\Usecases\Organization\Collaborators;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Organization\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\Organization\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorByIdUseCase
{
    private CollaboratorRepository $collaboratorRepository;
    private UserRequestContext $userContext;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->collaboratorRepository = $repositories->collaborator;
        $this->userContext = $userContext;
    }

    /**
     * @param string $id id of the collaborator to retrieve
     * @return PromiseInterface<CollaboratorEntity>
     */
    public function handle(string $id): PromiseInterface
    {
        return $this->collaboratorRepository
            ->getById($id, $this->userContext->getTenantId())
            ->then(static function (?CollaboratorEntity $collaborator) use ($id): CollaboratorEntity {
                if ($collaborator === null) {
                    throw new NotFoundException(sprintf('Collaborator "%s" not found.', $id));
                }

                return $collaborator;
            });
    }
}
