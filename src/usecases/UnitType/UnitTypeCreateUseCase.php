<?php

namespace Vertuoza\UseCases\UnitType;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class UnitTypeCreateUseCase
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
     *
     * @return PromiseInterface<UnitTypeEntity>
     */
    public function handle(
        string $name
    ): PromiseInterface
    {
        return async(
            [$this->unitTypeRepository, 'create']
        )([
            'label' => $name
        ]);
    }
}
