<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Libs\Exceptions\NotFoundException;
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
   * @param array{name: string} $input
   * @return PromiseInterface<UnitTypeEntity>
   */
  public function handle(array $input): PromiseInterface
  {
    $data = new UnitTypeMutationData();
    $data->name = $input['name'];

    $errors = (new StringValidator('name', $data->name, 'input.name'))
      ->notEmpty(true)
      ->max(255)
      ->validate();

    if (count($errors) > 0) {
      throw new BadUserInputException($errors, 'UnitTypeCreateInput');
    }

    return $this->unitTypeRepository
      ->countUnitTypeWithLabel($data->name, $this->userContext->getTenantId())
      ->then(function ($query) use ($data) {
        if ($query->count() > 0) {
          throw new BadUserInputException(
            new FieldError('name', "A unit type with the name '{$data->name}' already exists", 'LABEL_EXISTS', 'input.name', []),
            'UnitTypeCreateInput'
          );
        }

        return $this->unitTypeRepository->create($data, $this->userContext->getTenantId());
      })
      ->then(fn (string $id) => $this->unitTypeRepository->getById($id, $this->userContext->getTenantId()))
      ->then(function ($unitType) {
        if ($unitType === null) {
          throw new NotFoundException();
        }
        return $unitType;
      });
  }
}
