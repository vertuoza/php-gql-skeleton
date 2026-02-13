<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
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

  public function handle(array $input)
  {
    $name = trim((string) ($input['name'] ?? ''));

    $errors = (new StringValidator('name', $name, 'input'))
      ->notEmpty(true)
      ->max(255)
      ->validate();

    if (!empty($errors)) {
      throw new BadUserInputException($errors, 'UnitTypeCreateInput');
    }

    $tenantId = $this->userContext->getTenantId();

    return $this->unitTypeRepository
      ->countUnitTypeWithLabel($name, $tenantId)
      ->then(function ($count) use ($name, $tenantId) {
        if ($count > 0) {
          throw new BadUserInputException(
            new FieldError('name', 'A unit type with this name already exists', 'ALREADY_EXISTS', 'unit_type.name', ['name' => $name]),
            'UnitTypeCreateInput'
          );
        }

        $data = new UnitTypeMutationData();
        $data->name = $name;

        $newId = $this->unitTypeRepository->create($data, $tenantId);

        return $this->unitTypeRepository->getById((string) $newId, $tenantId);
      });
  }
}
