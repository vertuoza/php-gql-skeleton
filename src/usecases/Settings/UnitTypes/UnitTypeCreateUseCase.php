<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeCreateUseCase
{
    private RepositoriesFactory $repositories;
    private UserRequestContext $userContext;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->repositories = $repositories;
        $this->userContext = $userContext;
    }

    public function handle(array $input)
    {
        $tenantId = $this->userContext->getTenantId();

        $data = new UnitTypeMutationData(
            name: $input['name'],
        );

        $newId = $this->repositories
            ->unitType
            ->create($data, $tenantId);

        return [
            'status' => 'created',
            'name' => $data->name,
        ];
    }
}