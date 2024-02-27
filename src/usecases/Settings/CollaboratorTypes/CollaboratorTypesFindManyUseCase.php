<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class CollaboratorTypesFindManyUseCase
{
    private UserRequestContext $userContext;
    private CollaboratorRepository $collaboratorRepository;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
    ) {
        $this->collaboratorRepository = $repositories->collaboratorType;
        $this->userContext = $userContext;
    }

    /**
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle()
    {
        return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
    }
}