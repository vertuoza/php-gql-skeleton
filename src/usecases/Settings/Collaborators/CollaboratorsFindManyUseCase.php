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

/** Resolver for collaborators query */
class CollaboratorsFindManyUseCase
{
    private readonly CollaboratorRepository $repository;

    public function __construct(
        RepositoriesFactory $repositories,
        private readonly UserRequestContext $userContext
    ) {
        $this->repository = $repositories->collaborator;
    }

    /**
     * Resolve all collaborators accessible by the current user
     * @return PromiseInterface<CollaboratorEntity[]>
     * @throws InvalidArgumentException
     */
    public function handle(): PromiseInterface
    {
        $tenantId = $this->userContext->getTenantId();
        if ($tenantId === null) {
            return new FulfilledPromise([]);
        }

        return $this->repository->getByTenantId($tenantId);
    }
}
