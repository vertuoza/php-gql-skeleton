<?php

namespace Vertuoza\UseCases\UnitType;

use React\Promise\Promise;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class UnitTypeByIdUseCase
{
    /**
     * @var UnitTypeRepository
     */
    private UnitTypeRepository $unitTypeRepository;


    /**
     * @param UnitTypeRepository $unitTypeRepository
     */
    public function __construct(
        UnitTypeRepository $unitTypeRepository
    ) {
        $this->unitTypeRepository = $unitTypeRepository;
    }

    /**
     * @param string $id id of the unit type to retrieve
     *
     * @return Promise<UnitTypeEntity>
     */
    public function handle(string $id): Promise
    {
        return async(
            [$this->unitTypeRepository, 'find']
        )($id);
    }
}
