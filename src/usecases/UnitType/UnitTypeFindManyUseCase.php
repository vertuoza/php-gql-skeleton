<?php

namespace Vertuoza\UseCases\UnitType;

use React\Promise\PromiseInterface;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class UnitTypeFindManyUseCase
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
     * @return PromiseInterface
     */
    public function handle(): PromiseInterface
    {
        return async(
            [$this->unitTypeRepository, 'findMany']
        )();
    }
}
