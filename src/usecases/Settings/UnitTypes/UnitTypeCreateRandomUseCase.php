<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

use function React\Async\async;
use function React\Async\await;

class UnitTypeCreateRandomUseCase
{
  private UnitTypeRepository $unitTypeRepository;
  private UserRequestContext $userContext;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->unitTypeRepository = $repositories->unitType;
    $this->userContext = $userContext;
  }

  public function handle(): Promise
  {
    $tenantId = $this->userContext->getTenantId();

    return async(function () use ($tenantId) {
      
      $data = new UnitTypeMutationData();
      $data->name = self::RandomString();

      $newId = $this->unitTypeRepository->create($data, $tenantId);

      return await($this->unitTypeRepository->getById($newId, $tenantId));
    })();
  }

   public static function randomString(int $length = 10): string
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $max = strlen($characters) - 1;
      $result = '';
      for ($i = 0; $i < $length; $i++) {
        $result .= $characters[random_int(0, $max)];
      }
      return $result;
    }
}