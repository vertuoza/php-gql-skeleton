<?php

namespace Vertuoza\Factories;

use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Kernel;
use Vertuoza\Repositories\UnitTypeRepository;
use Vertuoza\Usecases\UnitType\UnitTypeUseCases;

/**
 * @deprecated move to kernel
 */
readonly class UseCasesFactory
{
    /**
     * @var UnitTypeUseCases $unitType
     */
    public UnitTypeUseCases $unitType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unitType = new UnitTypeUseCases($this->getRepository());
    }

    /**
     * @return UnitTypeRepository
     *
     * @throws \Exception
     */
    public function getRepository(): UnitTypeRepository
    {
        $kernel = Kernel::getInstance();
        $repository = $kernel->getRepository(UnitTypeEntity::class);

        if (!$repository instanceof UnitTypeRepository) {
            throw new \Exception();
        }

        return $repository;
    }
}
