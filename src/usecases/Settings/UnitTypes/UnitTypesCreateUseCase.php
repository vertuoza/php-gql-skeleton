<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class UnitTypesCreateUseCase
{
  private UserRequestContext $userContext;
  private UnitTypeRepository $unitTypeRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext,
  ) {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }
  public function handle(string $label): Promise
  {
    $tenantId = $this->userContext->getTenantId();

    $data = new UnitTypeMutationData();
    $data->label = $label;

    $id = $this->unitTypeRepository->create($data, $tenantId);

    return $this->unitTypeRepository->getById((string) $id, $tenantId);

  }
}
