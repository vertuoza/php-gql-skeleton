<?php

namespace Vertuoza\Usecases;

use React\Promise\Promise;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Api\Graphql\Context\UserRequestContext;

enum RepositoryType {
    case Unit;
    case Collaborator;
}

class UseCase
{
    protected UserRequestContext $context;
    protected UnitTypeRepository | CollaboratorRepository $repository;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
        RepositoryType $repositoryType
    ) {
        $this->context = $userContext;
        match ($repositoryType){
            RepositoryType::Unit => $this->repository = $repositories->unitType,
            RepositoryType::Collaborator => $this->repository = $repositories->collaborator
        };
    }
    /**
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle(string | null $id = null): Promise {
        return async()(function (){});
    }
}

