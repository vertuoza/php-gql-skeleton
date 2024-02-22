<?php

declare(strict_types=1);

namespace Vertuoza\Usecases\Settings\Collaborators;

use InvalidArgumentException;
use React\Promise\Internal\FulfilledPromise;
use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

/** Resolver for collaborator(id: ID!) query */
class CollaboratorByIdUseCase
{
    private readonly CollaboratorRepository $repository;

    public function __construct(
        RepositoriesFactory $repositories,
        private readonly UserRequestContext $userContext
    ) {
        $this->repository = $repositories->collaborator;
    }

    /**
     * Resolve a collaborator by its id
     * @return PromiseInterface<CollaboratorEntity|null>
     * @throws InvalidArgumentException
     */
    public function handle(string $id): PromiseInterface
    {
        $tenantId = $this->userContext->getTenantId();
        if ($tenantId === null) {
            return new FulfilledPromise(null);
        }

        return $this->repository->getByIdAndTenantId($id, $tenantId);
    }
}
