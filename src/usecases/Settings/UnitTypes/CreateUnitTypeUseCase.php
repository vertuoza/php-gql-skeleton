<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Api\Graphql\Errors\GqlClientError;

use function React\Async\async;
use function React\Async\await;

class CreateUnitTypeUseCase
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

    public function handle(string $name): Promise
    {
        return async(function () use ($name) {
            $tenantId = $this->userContext->getTenantId();

            $count = await($this->unitTypeRepository->countUnitTypeWithLabel(
                $name,
                $tenantId
            ))->count();

            if ($count > 0) {
                throw new GqlClientError(
                    message: "Un type d'unité avec ce nom existe déjà",
                    errorCode: "DUPLICATE_UNIT_TYPE_NAME",
                    args: ['name' => $name],
                    fields: ['name']
                );
            }

            return await($this->unitTypeRepository->create(
                new UnitTypeMutationData($name),
                $tenantId
            ));
        })();
    }
}
