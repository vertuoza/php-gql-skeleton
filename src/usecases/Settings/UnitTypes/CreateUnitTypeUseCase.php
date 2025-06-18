<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Usecases\UseCase;
use React\Promise\Promise;

class CreateUnitTypeUseCase extends UseCase
{
    public function setAndGetUnitType(UnitTypeMutationData $data): UnitTypeEntity
    {
        if ($data->name == '') throw new Exception("The empty string cannot be use as valid UnitType name");
        $tenantId = $this->context->getTenantId();
        $item = $this->repository->findByName($data, $tenantId);
        if (is_null($item)) {
            $item = $this->repository->create($data, $tenantId);
            if (is_null($item)) throw new Exception("Cannot create the UnityType {$data->name}, please try later.");
        }
        return $item;
    }
}