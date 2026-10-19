<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
{
  private const INPUT_NAME = 'UnitTypeCreateInput';
  private const NAME_MAX_LENGTH = 255; // size of the `label` column

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
   * @param UnitTypeMutationData $data the unit type to create
   * @return PromiseInterface<UnitTypeEntity> the created unit type
   * @throws BadUserInputException when the name is empty, too long or already used
   */
  public function handle(UnitTypeMutationData $data): PromiseInterface
  {
    $data->name = trim($data->name);

    $errors = (new StringValidator('name', $data->name))
      ->notEmpty()
      ->max(self::NAME_MAX_LENGTH)
      ->validate();
    if (!empty($errors)) {
      throw new BadUserInputException($errors, self::INPUT_NAME);
    }

    $tenantId = $this->userContext->getTenantId();

    return $this->unitTypeRepository
      ->countUnitTypeWithLabel($data->name, $tenantId)
      ->then(function (int $sameNameCount) use ($data, $tenantId) {
        if ($sameNameCount > 0) {
          throw new BadUserInputException(
            new FieldError('name', 'A unit type with this name already exists', 'ALREADY_EXISTS'),
            self::INPUT_NAME
          );
        }

        $id = $this->unitTypeRepository->create($data, $tenantId);

        return $this->unitTypeRepository->getById($id, $tenantId);
      });
  }
}
