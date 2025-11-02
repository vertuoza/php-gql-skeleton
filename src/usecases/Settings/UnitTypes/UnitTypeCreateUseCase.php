<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\BusinessException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use React\Promise\Promise;
use function React\Async\async;
use function React\Async\await;

class UnitTypeCreateUseCase
{
    private UnitTypeRepository $unitTypeRepository;

    public function __construct(
        RepositoriesFactory $repositories,
        private UserRequestContext $userContext,
    ) {
        $this->unitTypeRepository = $repositories->unitType;
    }

    /**
     * Create a new UnitType from a name
     * @param string $name
     * @return Promise<UnitTypeEntity>
     * @throws BadUserInputException
     * @throws BusinessException
     */
    public function handle(string $name): Promise
    {
        return async(function () use ($name) {
            $name = trim($name);

            // Check name is not empty
            if ($name === '') {
                throw new BadUserInputException(
                    [new FieldError('name', 'The name cannot be empty')],
                    'name'
                );
            }

            // Check 'name' does not exceed 255 characters
            if (strlen($name) > 255) {
                throw new BadUserInputException(
                    [new FieldError('name', 'The name must be less than 255 characters')],
                    'name'
                );
            }

            $tenantId = $this->userContext->getTenantId();

            // Check if UnitType with the same name doesn't already exist
            $exists = await($this->unitTypeRepository->labelExists($name, $tenantId));
            if ($exists) {
                throw new BusinessException(
                    "Unit type with name $name already exists",
                    400
                );
            }

            $unitType = new UnitTypeMutationData();
            $unitType->name = $name;

            $unitTypeId = await($this->unitTypeRepository->create($unitType, $tenantId));

            $entity = new UnitTypeEntity();
            $entity->id = $unitTypeId;
            $entity->name = $name;
            $entity->isSystem = $tenantId === null;

            return $entity;
        })();
    }
}