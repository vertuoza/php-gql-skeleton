<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
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

  /**
   * @param array $input data of the unit type to create
   * @return Promise<UnitTypeEntity>
   * @throws BadUserInputException
   */
  public function handle(array $input): PromiseInterface
  {
    $name = trim($input['name']);

    $errors = (new StringValidator('name', $name))
      ->notEmpty()
      ->max(255)
      ->validate();

    if (count($errors) > 0) {
      throw new BadUserInputException($errors, 'UnitTypeCreateInput');
    }

    $tenantId = $this->userContext->getTenantId();

    $data = new UnitTypeMutationData();
    $data->name = $name;

    $newId = $this->unitTypeRepository->create($data, $tenantId);

    return $this->unitTypeRepository
      ->getById($newId, $tenantId)
      ->then(function (?UnitTypeEntity $unitType) use ($newId) {
        if ($unitType === null) {
          throw new \RuntimeException("Unit type {$newId} could not be read back after creation");
        }

        return $unitType;
      });
  }
}
