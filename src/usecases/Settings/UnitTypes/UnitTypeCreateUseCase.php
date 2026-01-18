<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use function React\Async\async;
use function React\Async\await;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeCreateUseCase
{
    public function __construct(
        private RepositoriesFactory $repositories,
        private UserRequestContext $userContext
    ) {}

    public function execute(array $input)
    {
        return async(function () use ($input) {
            $tenantId = $this->userContext->getTenantId();
            $data = new UnitTypeMutationData($input['name']);

            await($this->repositories->unitType->create($data, $tenantId));

            $entity = await($this->repositories->unitType->getByLabel($input['name'], $tenantId));

            if (!$entity) {
                throw new \Exception("Erreur lors de la récupération de l'unité créée.");
            }

            return $entity;
        })();
    }
}