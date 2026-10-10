<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\PromiseInterface;
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

  public function handle(UnitTypeMutationData $data): PromiseInterface
  {
    $errors = (new StringValidator('name', $data->name))
      ->notEmpty(true)
      ->max(255)
      ->validate();

    if (!empty($errors)) {
      throw new BadUserInputException($errors, 'UnitTypeCreateInput');
    }

    $tenantId = $this->userContext->getTenantId();

    return $this->unitTypeRepository
      ->create($data, $tenantId)
      ->then(function (string $id) use ($data) {
        $entity = new UnitTypeEntity();
        $entity->id = $id;
        $entity->name = $data->name;
        $entity->isSystem = false;

        return $entity;
      });
  }
}
