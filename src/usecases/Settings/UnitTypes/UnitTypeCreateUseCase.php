<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\BusinessException;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

use function React\Async\async;
use function React\Async\await;
class UnitTypeCreateUseCase
{
    private UnitTypeRepository $unitTypeRepository;
    private UserRequestContext $userContext;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->unitTypeRepository = $repositories->unitType;
        $this->userContext = $userContext;
    }

    /**
     * @param string $name
     * @return Promise
     * @throws BadUserInputException
     */
    public function handle(string $name): Promise
    {
        return async(function () use ($name) {
            $name = trim($name);

            // Check if name is valid
            $errors = (new StringValidator('name', $name))
                ->notEmpty()
                ->max(255)
                ->min(1)
                ->matches(
                    "/^[\p{L}\p{N} _'().\/-]+$/u",
                    'Name should only contain letters, numbers, spaces, apostrophes, parentheses, periods, and hyphens'
                )
                ->validate();

            if (!empty($errors)) {
                throw new BadUserInputException($errors, 'name');
            }

            // Then check if unit type with this name already exists
            $tenantId = $this->userContext->getTenantId();

            $existingUnitTypesCount = await(
                $this->unitTypeRepository->countUnitTypeWithLabel($name, $tenantId)
            );

            if ($existingUnitTypesCount > 0) {
                throw new BusinessException(
                    "A unit type with this name already exists",
                    "UNIT_TYPE_NAME_ALREADY_EXISTS",
                    409,
                    null,
                    [
                        "field" => "name",
                        "value" => $name,
                    ]
                );
            }

            $data = new UnitTypeMutationData();
            $data->name = $name;

            $newId = $this->unitTypeRepository->create($data, $tenantId);

            return await($this->unitTypeRepository->getById((string)$newId, $tenantId));
        })();
    }
}
