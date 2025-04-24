<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

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
   * @param string $name name of the unit type to create
   *
   * @return Promise<UnitTypeEntity>
   */
  public function handle(string $name): Promise
  {
    $validator = new StringValidator('name', $name, "input");
    $errors = $validator->notEmpty(true)->format('/^[0-9a-z \-]+$/i', 'letters, numbers, spaces and hyphens')->validate();

    if (!empty($errors)) {
      throw new BadUserInputException($errors, 'name');
   }

    $mutationData = new UnitTypeMutationData();
    $mutationData->name = $name;
    $newId = $this->unitTypeRepository->create($mutationData, $this->userContext->getTenantId());

    return $this->unitTypeRepository->getById((string) $newId, $this->userContext->getTenantId());
  }
}
