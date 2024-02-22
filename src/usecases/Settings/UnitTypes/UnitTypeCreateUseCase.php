<?php

declare(strict_types=1);

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Exception;
use GraphQL\Error\Error;
use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Validator\InputDataValidator;
use Vertuoza\Libs\Validator\ValidatorFactory;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

/** Resolver for a unitTypeCreate mutation */
class UnitTypeCreateUseCase
{
    private UnitTypeRepository $repository;

    public function __construct(
        RepositoriesFactory $repositories,
        private readonly UserRequestContext $userContext
    ) {
        $this->repository = $repositories->unitType;
    }

    /**
     * Create a new unit type.
     * @return PromiseInterface<UnitTypeEntity|null>
     * @throws Exception
     */
    public function handle(UnitTypeMutationData $mutationData): PromiseInterface
    {
        // Validate the input
        $validator = new InputDataValidator(ValidatorFactory::build());
        $validator->validate($mutationData);

        // Additional checks
        $tenantId = $this->userContext->getTenantId();
        if ($tenantId === null) {
            throw new Error('The method does not support creation of system unit types');
        }

        // Create a new unit type & return it
        $id = $this->repository->create($mutationData, $tenantId);

        return $this->repository->getById((string)$id, $tenantId);
    }
}
