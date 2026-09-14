<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
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
     * @param UnitTypeMutationData $unitType
     * @return PromiseInterface<UnitTypeEntity>
     * @throws BadUserInputException
     */
    public function handle(UnitTypeMutationData $unitType): PromiseInterface
    {
        return async(function () use ($unitType) {
            $tenantId = $this->userContext->getTenantId();
            $trimmedName = trim($unitType->name ?? '');

            if (empty($trimmedName)) {
                $error = new FieldError('name', 'The unit type name cannot be empty', 'EMPTY', '', []);
                throw new BadUserInputException([$error], 'UnitTypeCreateInput');
            }

            $unitType->name = $trimmedName;

            $count = await($this->unitTypeRepository->countUnitTypeWithLabel($trimmedName, $tenantId));
            if ($count > 0) {
                $error = new FieldError('name', "A unit type with the name '{$trimmedName}' already exists", 'ALREADY_EXISTS', '', []);
                throw new BadUserInputException([$error], 'UnitTypeCreateInput');
            }

            $newId = $this->unitTypeRepository->create($unitType, $tenantId);

            return await($this->unitTypeRepository->getById((string) $newId, $tenantId));
        })();
    }
}