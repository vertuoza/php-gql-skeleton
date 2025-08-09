<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class CreateUnitTypeUseCase
{
  public function __construct(
    private UnitTypeRepository $unitTypeRepository,
    private UserRequestContext $userContext,
  )
  {
  }

  public function handle(UnitTypeMutationData $data): string
  {
    return $this->unitTypeRepository->create($data, $this->userContext->getTenantId());
  }
}
