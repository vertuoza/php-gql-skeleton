<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\BusinessException;
use Vertuoza\Libs\Validation\InputStringValidator;
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
     * Use Case to create a new UnitType from a name
     * @param string $name
     * @return Promise<UnitTypeEntity>
     * @throws BadUserInputException
     * @throws BusinessException
     */
    public function handle(string $name): Promise
    {
        return async(function () use ($name) {
            $name = trim($name);

            // Check if $name doesn't contain invalid characters
            // like <, >, ', ", &, (, ), ;
            $pattern = '/^[\p{L}\p{N}\s\-_.]+$/u';

            // Check the validity of the name
            InputStringValidator::validate(string: $name, fieldName: 'name', pattern: $pattern);


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

            // We already have all the information we need,
            // so we don't need to fetch the UnitType
            $entity = new UnitTypeEntity();
            $entity->id = $unitTypeId;
            $entity->name = $name;
            $entity->isSystem = $tenantId === null;

            return $entity;
        })();
    }
}