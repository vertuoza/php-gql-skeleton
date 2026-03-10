<?php
namespace Vertuoza\Usecases\Settings\UnitTypes;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
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
  public function handle(array $input)
  {
  return async(function () use ($input) {
    $data = new UnitTypeMutationData();
    $data->name = $input['name'];

    $this->unitTypeRepository->create($data, $this->userContext->getTenantId());

    $entities = await($this->unitTypeRepository->findMany($this->userContext->getTenantId()));

    return $entities->last();
  })();
  }
}