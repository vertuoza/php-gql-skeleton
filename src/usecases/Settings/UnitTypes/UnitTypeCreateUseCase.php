<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

use function React\Async\async;
use function React\Async\await;

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
   * @return Promise<UnitTypeEntity>
   */
  public function handle(UnitTypeMutationData $data): Promise
  {
    return async(function () use ($data) {
      $errors = (new StringValidator('name', $data->name))
        ->notEmpty(true)
        ->max(255)
        ->validate();

      if (!empty($errors)) {
        throw new BadUserInputException($errors, 'UnitTypeCreateInput');
      }

      $count = await($this->unitTypeRepository->countUnitTypeWithLabel(
        $data->name,
        $this->userContext->getTenantId()
      ));

      if ($count > 0) {
        throw new BadUserInputException(
          new FieldError('name', 'A unit type with this name already exists'),
          'UnitTypeCreateInput'
        );
      }

      return await($this->unitTypeRepository->create($data, $this->userContext->getTenantId()));
    })();
  }
}
