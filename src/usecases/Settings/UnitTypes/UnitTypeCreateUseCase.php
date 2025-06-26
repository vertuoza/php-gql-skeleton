<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Illuminate\Support\Facades\App;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadRequestException;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Logger\ApplicationLogger;
use Vertuoza\Libs\Logger\LogContext;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;

class UnitTypeCreateUseCase
{
  private UserRequestContext $userContext;
  private UnitTypeRepository $unitTypeRepository;

  public function __construct(
    RepositoriesFactory $repositories,
    UserRequestContext $userContext,
  ) {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }

  /**
   * @param UnitTypeMutationData $data
   * @return PromiseInterface<int>
   */
  public function handle(UnitTypeMutationData $data): PromiseInterface
  {
    $tenantId = $this->userContext->getTenantId();

    return $this->unitTypeRepository->countUnitTypeWithLabel(
      $data->name,
      $tenantId
    )
    ->then(function ($count) use ($data, $tenantId) {
      if( $count > 0) {
        ApplicationLogger::getInstance()->warning("Unit type with label '{$data->name}' already exists.", new LogContext($tenantId, null));
        throw new BadRequestException("Unit type with label '{$data->name}' already exists.");
      }

      ApplicationLogger::getInstance()->info("Creating unit type with label '{$data->name}'", new LogContext($tenantId, null));
      return $this->unitTypeRepository->create(
        $data,
        $tenantId
      );
    });
  }
}
