<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadRequestException;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

use function React\Async\async;
use function React\Async\await;

class UnitTypeCreateUseCase
{
  private UnitTypeRepository $unitTypeRepository;
  private UserRequestContext $userContext;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }

  /** @param array{name: string} $input */
  public function handle(array $input): Promise
  {
    $tenantId = $this->userContext->getTenantId();

    return async(function () use ($input, $tenantId) {
      if (trim($input['name']) === '') {
        throw new BadRequestException(
          "Unit type name cannot be empty.",
          "INVALID_NAME",
          null,
          ['name' => $input['name']]
        );
      }

      $count = await($this->unitTypeRepository->countUnitTypeWithLabel($input['name'], $tenantId))->count();

      if ($count > 0) {
        throw new BadRequestException(
          "A unit type named \"{$input['name']}\" already exists.",
          "UNIT_TYPE_ALREADY_EXISTS",
          null,
          ['name' => $input['name']]
        );
      }

      $data = new UnitTypeMutationData();
      $data->name = $input['name'];

      $newId = $this->unitTypeRepository->create($data, $tenantId);

      return await($this->unitTypeRepository->getById($newId, $tenantId));
    })();
  }
}