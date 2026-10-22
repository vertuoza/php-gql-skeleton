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

use function React\Promise\reject;

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
   * @param UnitTypeMutationData $data unit type to create
   * @return PromiseInterface<UnitTypeEntity>
   */
  public function handle(UnitTypeMutationData $data): PromiseInterface
  {
    $data->name = trim($data->name);

    $errors = (new StringValidator('name', $data->name))
      ->notEmpty()
      ->max(255)
      ->validate();
    if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $data->name)) {
      $errors[] = new FieldError(
        'name',
        'Field cannot contain emojis or other 4-byte characters',
        'UNSUPPORTED_CHARACTERS'
      );
    }
    if (count($errors) > 0) {
      return reject(new BadUserInputException($errors, 'UnitTypeCreateInput'));
    }

    $tenantId = $this->userContext->getTenantId();

    return $this->unitTypeRepository
      ->countUnitTypeWithLabel($data->name, $tenantId)
      ->then(function (int $count) use ($data, $tenantId) {
        if ($count > 0) {
          throw new BadUserInputException(
            new FieldError('name', 'A unit type with this name already exists', 'ALREADY_EXISTS'),
            'UnitTypeCreateInput'
          );
        }

        $id = $this->unitTypeRepository->create($data, $tenantId);

        return $this->unitTypeRepository->getById($id, $tenantId);
      });
  }
}
